<?php

namespace App\Services;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use App\Services\WhatJobs\HomePageJobService;
use App\Services\WhatJobs\WhatJobsSearchPageCacheService;

class WhatJobsSyncService
{
    public function __construct(
        protected WhatJobsService $whatJobs,
        protected SitemapService $sitemapService,
        protected HomePageJobService $homePageJobService,
        protected WhatJobsSearchPageCacheService $searchPageCache
    ) {
    }

    /**
     * Sync all India jobs from WhatJobs.
     *
     * is_active convention (standard Laravel boolean):
     *
     * true  = ACTIVE
     * false = INACTIVE / EXPIRED
     */
    public function syncIndiaJobs(string $userIp): array
    {
        $syncStartedAt = now();

        $totalProcessed = 0;
        $totalInserted = 0;
        $totalUpdated = 0;

        /*
        |--------------------------------------------------------------------------
        | Get first page
        |--------------------------------------------------------------------------
        */

        $firstPage = $this->whatJobs->getIndiaJobs(
            userIp: $userIp,
            userAgent: 'MyJobAlerts WhatJobs Sync',
            page: 1
        );

        /*
        |--------------------------------------------------------------------------
        | Read pagination information
        |--------------------------------------------------------------------------
        */

        $totalJobs = (int) ($firstPage['total'] ?? 0);
        $lastPage = (int) ($firstPage['last_page'] ?? 1);

        Log::info('WhatJobs sync started', [
            'total_jobs' => $totalJobs,
            'last_page' => $lastPage,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Process page 1
        |--------------------------------------------------------------------------
        */

        $result = $this->processPage(
            $firstPage,
            $syncStartedAt
        );

        $totalProcessed += $result['processed'];
        $totalInserted += $result['inserted'];
        $totalUpdated += $result['updated'];

        /*
        |--------------------------------------------------------------------------
        | Process remaining pages
        |--------------------------------------------------------------------------
        */

        for ($page = 2; $page <= $lastPage; $page++) {

            Log::info(
                "WhatJobs sync page {$page}/{$lastPage}"
            );

            $response = $this->whatJobs->getIndiaJobs(
                userIp: $userIp,
                userAgent: 'MyJobAlerts WhatJobs Sync',
                page: $page
            );

            $result = $this->processPage(
                $response,
                $syncStartedAt
            );

            $totalProcessed += $result['processed'];
            $totalInserted += $result['inserted'];
            $totalUpdated += $result['updated'];
        }

        /*
        |--------------------------------------------------------------------------
        | Deactivate jobs that were not seen
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | This runs ONLY after every WhatJobs page has been processed.
        |
        | is_active:
        |
        | true  = ACTIVE
        | false = INACTIVE / EXPIRED
        |
        | Therefore:
        |
        | Find active jobs that were not seen during this sync
        | and mark them inactive.
        |
        */

        $deactivated = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', true)
            ->where(function ($query) use ($syncStartedAt) {
                $query
                    ->whereNull('last_seen_at')
                    ->orWhere(
                        'last_seen_at',
                        '<',
                        $syncStartedAt
                    );
            })
            ->update([
                'is_active' => false,
                'updated_at' => now(),
            ]);

        /*
        |--------------------------------------------------------------------------
        | Generate dynamic sitemap
        |--------------------------------------------------------------------------
        |
        | Sitemap generation happens AFTER:
        |
        | 1. All WhatJobs pages are processed
        | 2. New jobs are inserted
        | 3. Existing jobs are updated
        | 4. Missing jobs are marked inactive
        |
        | Sitemap should therefore contain only:
        |
        | is_active = true
        |
        */

        $this->sitemapService->generate();

        /*
        |--------------------------------------------------------------------------
        | Refresh homepage cache
        |--------------------------------------------------------------------------
        */

        $this->homePageJobService->refreshHomepageCache();

        /*
        |--------------------------------------------------------------------------
        | Refresh WhatJobs search-page cache version
        |--------------------------------------------------------------------------
        |
        | This happens only after the complete WhatJobs sync
        | has successfully processed all pages.
        |
        */

        $searchCacheVersion = $this->searchPageCache->newVersion();

        Log::info('WhatJobs search cache version refreshed', [
            'version' => $searchCacheVersion,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Log completed sync
        |--------------------------------------------------------------------------
        */

        Log::info('WhatJobs sync completed', [
            'total_jobs' => $totalJobs,
            'pages' => $lastPage,
            'processed' => $totalProcessed,
            'inserted' => $totalInserted,
            'updated' => $totalUpdated,
            'deactivated' => $deactivated,
        ]);

        return [
            'total_jobs' => $totalJobs,
            'pages' => $lastPage,
            'processed' => $totalProcessed,
            'inserted' => $totalInserted,
            'updated' => $totalUpdated,
            'deactivated' => $deactivated,
        ];
    }

    /**
     * Process one page of WhatJobs results.
     *
     * is_active convention (standard Laravel boolean):
     *
     * true  = ACTIVE
     * false = INACTIVE / EXPIRED
     */
    protected function processPage(
        array $response,
        Carbon $syncStartedAt
    ): array {
        $jobs = $response['data'] ?? [];

        $processed = 0;
        $inserted = 0;
        $updated = 0;

        foreach ($jobs as $jobData) {

            /*
            |--------------------------------------------------------------------------
            | Skip invalid records
            |--------------------------------------------------------------------------
            */

            if (
                empty($jobData['url']) ||
                empty($jobData['title'])
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Extract WhatJobs job ID
            |--------------------------------------------------------------------------
            */

            $providerJobId = $this->extractProviderJobId(
                $jobData['url']
            );

            if (!$providerJobId) {

                Log::warning(
                    'Could not extract WhatJobs job ID',
                    [
                        'url' => $jobData['url'],
                        'title' => $jobData['title'],
                    ]
                );

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Generate MyJobAlerts slug
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | Associate | QA Engineer
            |
            | becomes:
            |
            | associate-qa-engineer-242331104
            |
            */

            $slug = Str::slug(
                $jobData['title']
            ) . '-' . $providerJobId;

            /*
            |--------------------------------------------------------------------------
            | Find existing job
            |--------------------------------------------------------------------------
            */

            $job = Job::query()
                ->where('provider', 'whatjobs')
                ->where(
                    'provider_job_id',
                    $providerJobId
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Derive additional attributes from the snippet
            |--------------------------------------------------------------------------
            |
            | WhatJobs does not send remote/employment-type/salary as
            | structured fields. These are parsed from labeled key-value
            | patterns inside the snippet text (e.g. "Type: Contract",
            | "Compensation: $80-$120/hour", "Location: Remote").
            |
            | strip_tags() is run once here and the plain text is passed
            | to all three extractors, instead of each extractor stripping
            | tags from the same snippet independently.
            |
            | Extraction can fail to find a match, in which case null
            | (or false for is_remote) is stored rather than a guess.
            |
            */

            $snippetText = strip_tags($jobData['snippet'] ?? '');

            $rawJobType = $this->extractJobType($snippetText);

            $salaryData = $this->extractSalary($snippetText);

            /*
            |--------------------------------------------------------------------------
            | Prepare job data
            |--------------------------------------------------------------------------
            */

            $attributes = [
                'title' => $jobData['title'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | MyJobAlerts SEO slug
                |--------------------------------------------------------------------------
                */

                'slug' => $slug,

                'company' => $jobData['company'] ?? null,
                'location' => $jobData['location'] ?? null,

                'is_remote' => $this->extractRemoteSignal(
                    $jobData['title'] ?? null,
                    $snippetText
                ),

                'postcode' => $jobData['postcode'] ?? null,
                'job_type' => $jobData['job_type'] ?? null,
                'employment_type' => $this->mapEmploymentType($rawJobType),

                'salary' => $jobData['salary'] ?? null,
                'salary_min' => $salaryData['min'] ?? null,
                'salary_max' => $salaryData['max'] ?? null,
                'salary_currency' => $salaryData['currency'] ?? null,
                'salary_unit' => $salaryData['unit'] ?? null,

                'snippet' => $jobData['snippet'] ?? null,
                'logo' => $jobData['logo'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Keep the exact WhatJobs URL
                |--------------------------------------------------------------------------
                */

                'job_url' => $jobData['url'],

                'age_days' => isset($jobData['age_days'])
                    ? (int) $jobData['age_days']
                    : null,

                /*
                |--------------------------------------------------------------------------
                | Mark this job as seen during this sync
                |--------------------------------------------------------------------------
                */

                'last_seen_at' => $syncStartedAt,

                'is_active' => true,
            ];

            /*
            |--------------------------------------------------------------------------
            | Published date
            |--------------------------------------------------------------------------
            */

            if (isset($jobData['age_days'])) {

                $attributes['published_at'] =
                    $syncStartedAt
                        ->copy()
                        ->subDays(
                            (int) $jobData['age_days']
                        );
            }

            /*
            |--------------------------------------------------------------------------
            | Update existing job
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | Do NOT change job_gfj_status here unless a previously
            | completed Google lifecycle job comes back.
            |
            */

            if ($job) {

                /*
                |--------------------------------------------------------------------------
                | If a previously inactive job comes back
                |--------------------------------------------------------------------------
                |
                | is_active === false means the job was previously inactive.
                |
                | If job_gfj_status is 3, the previous Google lifecycle
                | was completed.
                |
                | Therefore, if WhatJobs sends this job again, start a new
                | Google posting lifecycle with status 1.
                |
                */

                if (
                    $job->is_active === false &&
                    (int) $job->job_gfj_status === 3
                ) {
                    $attributes['job_gfj_status'] = 1;
                }

                $job->update($attributes);

                $updated++;
            }

            /*
            |--------------------------------------------------------------------------
            | Insert new job
            |--------------------------------------------------------------------------
            |
            | New job starts with:
            |
            | is_active = true
            | job_gfj_status = 1
            |
            | Meaning:
            |
            | Active and waiting to be processed for Google for Jobs.
            |
            */

            else {

                Job::create([
                    'provider' => 'whatjobs',
                    'provider_job_id' => $providerJobId,

                    ...$attributes,

                    'job_gfj_status' => 1,
                ]);

                $inserted++;
            }

            $processed++;
        }

        return [
            'processed' => $processed,
            'inserted' => $inserted,
            'updated' => $updated,
        ];
    }

    /**
     * Extract the WhatJobs job ID from the tracking URL.
     *
     * Example:
     *
     * https://en-in.whatjobs.com/
     * pub_api__cpl__241776915__7202?...
     *
     * Result:
     *
     * 241776915
     */
    protected function extractProviderJobId(
        string $url
    ): ?string {
        if (
            preg_match(
                '/pub_api__cpl__(\d+)__/i',
                $url,
                $matches
            )
        ) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Detect whether a job is remote.
     *
     * WhatJobs does not send a structured remote/telecommute field.
     *
     * Preference order:
     * 1. A labeled "Location:" field inside the snippet
     *    (e.g. "Location: Remote") — most reliable signal available.
     * 2. Fallback keyword scan across title + snippet text.
     *
     * $snippetText is expected to already be stripped of HTML tags.
     */
    protected function extractRemoteSignal(
        ?string $title,
        string $snippetText
    ): bool {
        if (
            preg_match(
                '/Location:\s*([^A-Z]{0,30}?)(?:[A-Z][a-z]+:|$)/',
                $snippetText,
                $matches
            )
        ) {
            $locationValue = trim($matches[1]);

            if (preg_match('/\bremote\b/i', $locationValue)) {
                return true;
            }

            if ($locationValue !== '') {
                // Explicit non-remote location label found — trust it.
                return false;
            }
        }

        $haystack = strtolower(
            ($title ?? '') . ' ' . $snippetText
        );

        return (bool) preg_match(
            '/\b(fully remote|100% remote|remote job|remote position|work from home|wfh|remote-first|telecommute)\b/i',
            $haystack
        );
    }

    /**
     * Extract job type from a labeled "Type:" field in the snippet.
     *
     * Example:
     * "... Type: Contract Compensation: $80-$120/hour ..."
     *
     * $snippetText is expected to already be stripped of HTML tags.
     */
    protected function extractJobType(string $snippetText): ?string
    {
        if (
            preg_match(
                '/Type:\s*([^A-Z]{0,30}?)(?:[A-Z][a-z]+:|$)/',
                $snippetText,
                $matches
            )
        ) {
            $value = trim($matches[1]);

            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    /**
     * Map a free-text job type to schema.org's fixed employmentType enum.
     *
     * Google only accepts:
     * FULL_TIME, PART_TIME, CONTRACTOR, TEMPORARY,
     * INTERN, VOLUNTEER, PER_DIEM, OTHER
     */
    protected function mapEmploymentType(?string $rawType): ?string
    {
        if (!$rawType) {
            return null;
        }

        $normalized = strtolower(trim($rawType));

        return match (true) {
            str_contains($normalized, 'full') => 'FULL_TIME',
            str_contains($normalized, 'part') => 'PART_TIME',
            str_contains($normalized, 'contract') => 'CONTRACTOR',
            str_contains($normalized, 'temp') => 'TEMPORARY',
            str_contains($normalized, 'intern') => 'INTERN',
            str_contains($normalized, 'volunteer') => 'VOLUNTEER',
            str_contains($normalized, 'per diem') => 'PER_DIEM',
            default => 'OTHER',
        };
    }

    /**
     * Extract salary range from a labeled compensation field in the snippet.
     *
     * Recognized labels: Compensation, Salary, Salary Range, Pay,
     * Pay Range, Wage, Remuneration, CTC, Rate.
     *
     * Example:
     * "Compensation: $80-$120/hour"
     * "Salary: ₹8,00,000 - ₹12,00,000 per annum"
     *
     * Returns null if no usable numeric range is found — do NOT
     * fall back to a 0.00 placeholder, since a zero salary is worse
     * for Google for Jobs than omitting baseSalary entirely.
     *
     * Note: CTC-labeled values are treated as baseSalary as-is, though
     * CTC in Indian listings often bundles benefits/employer contributions
     * and isn't strictly equivalent to gross salary.
     *
     * $snippetText is expected to already be stripped of HTML tags.
     */
    protected function extractSalary(string $snippetText): ?array
    {
        $labelPattern = '(?:Compensation|Salary(?:\s*Range)?|Pay(?:\s*Range)?|Wage|Remuneration|CTC|Rate)';

        if (
            !preg_match(
                '/' . $labelPattern . ':\s*([^A-Z]{0,60}?)(?:[A-Z][a-z]+:|$)/',
                $snippetText,
                $matches
            )
        ) {
            return null;
        }

        $raw = trim($matches[1]);

        if (
            !preg_match(
                '/([\$₹])\s?([\d,]+(?:\.\d+)?)\s*(?:-|–|to)\s*([\$₹]?)\s?([\d,]+(?:\.\d+)?)\s*\/?\s*(?:per\s*)?(hour|hr|year|yr|annum|month|mo|week|wk|day)?/i',
                $raw,
                $m
            )
        ) {
            return null;
        }

        $currencySymbol = $m[1];
        $minValue = (float) str_replace(',', '', $m[2]);
        $maxValue = (float) str_replace(',', '', $m[4]);
        $unitRaw = strtolower($m[5] ?? '');

        if ($minValue <= 0 && $maxValue <= 0) {
            return null;
        }

        $currency = $currencySymbol === '₹' ? 'INR' : 'USD';

        $unitTime = match (true) {
            str_starts_with($unitRaw, 'hour'), $unitRaw === 'hr' => 'HOUR',
            str_starts_with($unitRaw, 'year'), $unitRaw === 'yr', $unitRaw === 'annum' => 'YEAR',
            str_starts_with($unitRaw, 'month'), $unitRaw === 'mo' => 'MONTH',
            str_starts_with($unitRaw, 'week'), $unitRaw === 'wk' => 'WEEK',
            $unitRaw === 'day' => 'DAY',
            default => 'HOUR',
        };

        return [
            'currency' => $currency,
            'min' => $minValue,
            'max' => $maxValue,
            'unit' => $unitTime,
        ];
    }
}