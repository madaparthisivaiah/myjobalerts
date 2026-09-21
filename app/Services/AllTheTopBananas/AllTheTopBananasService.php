<?php

namespace App\Services\AllTheTopBananas;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class AllTheTopBananasService
{
    protected string $apiUrl;
    protected string $partner;
    protected string $guid;
    protected int $pageSize;

    public function __construct()
    {
        $this->apiUrl = rtrim(
            config('services.attb.api_url'),
            '/'
        );

        $this->partner = config('services.attb.partner');
        $this->guid = config('services.attb.guid');
        $this->pageSize = (int) config(
            'services.attb.page_size',
            100
        );
    }

    /**
     * Search ATTB jobs.
     *
     * $extraParams allows passing any additional raw query params the
     * API supports (e.g. ['Categories' => ['Technology']], ['Region'
     * => 'Karnataka']) without needing a dedicated method argument for
     * every filter the API exposes.
     */
    public function search(
        ?string $keywords = null,
        ?string $location = null,
        int $start = 0,
        ?int $size = null,
        array $extraParams = []
    ): array {
        $size ??= $this->pageSize;

        $params = array_merge([
            'Partner' => $this->partner,
            'Guid' => $this->guid,
            'Start' => $start,
            'Size' => $size,
            'CountryCode' => 'IN',
        ], $extraParams);

        if ($keywords !== null && trim($keywords) !== '') {
            $params['Keywords'] = trim($keywords);
        }

        if ($location !== null && trim($location) !== '') {
            $params['Location'] = trim($location);
        }

        $response = $this->request()
            ->get($this->apiUrl . '/jobs/search', $params);

        if ($response->failed()) {
            throw new RuntimeException(
                'ATTB API request failed. HTTP status: ' .
                $response->status() .
                '. Response: ' .
                $response->body(),
                $response->status()
            );
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new RuntimeException(
                'ATTB API returned an invalid response.'
            );
        }

        if (!empty($data['error'])) {
            throw new RuntimeException(
                'ATTB API error: ' . $data['error']
            );
        }
        return $data;
    }

    /**
     * ATTB's hard offset-pagination ceiling.
     *
     * Confirmed via the API's own validation error:
     *   "Start": "The field Start must be between 0 and 4999."
     *   "Size":  "Cannot page beyond 5000 results"
     *
     * So Start + Size can never exceed 5000, regardless of what
     * `totalResults` in a response claims is available (it can
     * report a much larger number, e.g. 10000, that is simply not
     * reachable through this offset-based pagination scheme).
     *
     * This is a limitation of ATTB's API, not something fixable
     * client-side, unless they expose a different (e.g. cursor or
     * token based) pagination method for deeper result sets.
     */
    protected const MAX_PAGINATION_RESULTS = 5000;

    /**
     * Known ATTB categories for India, confirmed via the `Categories`
     * aggregation (scoped with CountryCode=IN). Each category is
     * fetched as its own paginated slice, since ATTB's per-query
     * pagination is capped at MAX_PAGINATION_RESULTS regardless of
     * filters — slicing by category lets us reach categories whose
     * true count exceeds that cap in aggregate (e.g. Technology,
     * Business), while every smaller category is captured in full.
     *
     * NOTE: Technology and Business individually reported
     * totalResults = 10000 when queried alone, which itself looks
     * like a reporting ceiling rather than their true count — so
     * even category slicing does not guarantee those two categories
     * are captured in full, only that they get their own dedicated
     * 5000-job slice instead of competing with everything else for
     * the newest 5000 jobs overall.
     */
    protected const CATEGORIES = [
        'Technology',
        'Business',
        'Arts and Entertainment',
        'Finance and Insurance',
        'Education',
        'Construction',
        'Real Estate',
        'Healthcare',
        'Manufacturing',
        'Retail',
        'Government',
        'Sports and Recreation',
    ];

    /**
     * Fetch one filtered slice of India jobs, paginating safely up to
     * ATTB's confirmed ceiling of MAX_PAGINATION_RESULTS results.
     *
     * $extraParams is passed straight through to search() — e.g.
     * ['Categories' => ['Technology']] to scope this slice to a
     * single category.
     */
    protected function fetchPaginatedSlice(array $extraParams, string $sliceLabel): array
    {
        $jobs = [];
        $start = 0;
        $maxIterations = 200; // hard safety ceiling against runaway loops
        $iterations = 0;
        $lastTotalResults = 0;

        do {
            $iterations++;

            if ($iterations > $maxIterations) {
                Log::warning(
                    'ATTB pagination exceeded safety limit, stopping early.',
                    [
                        'slice' => $sliceLabel,
                        'max_iterations' => $maxIterations,
                        'jobs_collected' => count($jobs),
                        'start' => $start,
                    ]
                );

                break;
            }

            // Never request past ATTB's confirmed ceiling.
            if ($start >= self::MAX_PAGINATION_RESULTS) {
                break;
            }

            // Shrink the final page's Size so Start + Size never
            // exceeds the ceiling.
            $size = min(
                $this->pageSize,
                self::MAX_PAGINATION_RESULTS - $start
            );

            $data = $this->search(
                null,
                null,
                $start,
                $size,
                $extraParams
            );

            $matches = $data['matches'] ?? [];

            if (!is_array($matches) || empty($matches)) {
                break;
            }

            foreach ($matches as $job) {
                if (is_array($job)) {
                    $jobs[] = $job;
                }
            }

            $count = count($matches);
            $start += $count;

            $lastTotalResults = (int) ($data['totalResults'] ?? 0);

            if ($lastTotalResults > 0 && $start >= $lastTotalResults) {
                break;
            }

            if ($count < $size) {
                break;
            }

        } while (true);

        if ($lastTotalResults > self::MAX_PAGINATION_RESULTS) {
            Log::warning(
                'ATTB slice reports more jobs than its pagination allows retrieving.',
                [
                    'slice' => $sliceLabel,
                    'total_results_reported' => $lastTotalResults,
                    'jobs_retrieved' => count($jobs),
                    'max_pagination_results' => self::MAX_PAGINATION_RESULTS,
                ]
            );
        }

        return $jobs;
    }

    /**
     * Get all available India jobs, sliced by category.
     *
     * Loops every category in self::CATEGORIES, fetching each one as
     * its own paginated slice (safely under ATTB's per-query 5000
     * cap), and merges everything into one deduplicated list — a job
     * tagged to more than one category is only kept once.
     *
     * This reaches far more of ATTB's India inventory than a single
     * unfiltered query (which is itself capped at 5000 total), at the
     * cost of many more API calls per sync run (roughly one call per
     * 100 jobs per category, ~12 categories).
     */
    public function getIndiaJobs(): array
    {
        $jobsById = [];

        foreach (self::CATEGORIES as $category) {
            $slice = $this->fetchPaginatedSlice(
                ['Categories' => [$category]],
                $category
            );

            Log::info('ATTB category slice fetched.', [
                'category' => $category,
                'jobs_fetched' => count($slice),
            ]);

            foreach ($slice as $job) {
                $id = trim((string) ($job['id'] ?? ''));

                if ($id === '') {
                    continue;
                }

                // Keep the first occurrence; a job tagged to multiple
                // categories is only stored once.
                $jobsById[$id] ??= $job;
            }
        }

        return array_values($jobsById);
    }

    /**
     * Fetch full job details for a single job, live.
     *
     * GET /jobs/{id} returns the full description ("description" /
     * "descriptionHTML"), unlike /jobs/search, which only returns a
     * short ~250 character "summary" preview.
     *
     * Intended to be called on-demand (e.g. when a job's detail page
     * is viewed) and cached briefly by the caller, rather than during
     * bulk sync — fetching this for every job in every sync run would
     * mean thousands of extra API calls per run.
     *
     * Returns the decoded detail array, or null if the job could not
     * be fetched (expired, removed, or a transient API failure).
     * Callers should fall back to the already-stored summary/snippet
     * on null rather than showing an error to the user.
     */
    public function getJobDetail(string $id): ?array
    {
        try {
            $response = $this->request()
                ->timeout(15)
                ->get($this->apiUrl . '/jobs/' . $id, [
                    'Partner' => $this->partner,
                    'Guid' => $this->guid,
                ]);
        } catch (Throwable $e) {
            Log::warning('ATTB job detail request failed.', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);

            return null;
        }

        if (!$response->successful()) {
            Log::warning('ATTB job detail fetch failed.', [
                'id' => $id,
                'status' => $response->status(),
            ]);

            return null;
        }

        $data = $response->json();

        return is_array($data) ? $data : null;
    }

    /**
     * HTTP client.
     */
    protected function request(): PendingRequest
    {
        return Http::acceptJson()
            ->timeout(60)
            ->retry(3, 1000);
    }
}