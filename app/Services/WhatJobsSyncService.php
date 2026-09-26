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
        | Deactivate jobs that have not been seen for 48 hours
        |--------------------------------------------------------------------------
        |
        | A job can temporarily disappear from the WhatJobs feed because of:
        |
        | - pagination/result changes
        | - temporary feed/API issues
        | - provider-side feed changes
        |
        | Therefore, do NOT deactivate a job simply because it was missing
        | from the current sync.
        |
        | is_active:
        |
        | 1 = ACTIVE
        | 0 = INACTIVE / EXPIRED
        |
        | A WhatJobs job is deactivated only when it has not been seen
        | for at least 48 hours.
        |
        */

        $deactivated = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', 1)
            ->whereNotNull('last_seen_at')
            ->where(
                'last_seen_at',
                '<',
                $syncStartedAt->copy()->subHours(48)
            )
            ->update([
                'is_active' => 0,
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

            $snippetText = html_entity_decode(
                strip_tags($jobData['snippet'] ?? ''),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            );

            $titleText = html_entity_decode(
                strip_tags($jobData['title'] ?? ''),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            );

            $rawJobType = $this->extractJobType($snippetText);

            $salaryData = $this->extractSalary(
                $snippetText,
                $titleText
            );

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

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT:
                |
                | Do NOT set is_active here.
                |
                | Existing inactive jobs must remain inactive.
                |--------------------------------------------------------------------------
                */
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

                if ($job->is_active === 0 ) {
                    
                    if ((int) $job->job_gfj_status === 3) {
                        $attributes['job_gfj_status'] = 1;
                    }
                    $attributes['is_active'] = 1;
                }

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT CHANGE:
                |
                | is_active is NOT updated here.
                |
                | Existing active/inactive state is preserved.
                |--------------------------------------------------------------------------
                */

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

                    /*
                    |--------------------------------------------------------------------------
                    | New jobs are active.
                    |--------------------------------------------------------------------------
                    */

                    'is_active' => 1,
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
     * https://en-in.whatjobs.com/pub_api__cpl__241776915__7202?...
     *
     * Result:
     *
     * 241776915
     */
    protected function extractProviderJobId(string $url): ?string
    {
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
     * Known snippet field labels.
     *
     * Once HTML is stripped from the snippet, labeled fields sit back
     * to back with no separating punctuation, e.g.:
     *
     * "Type: Contract Compensation: $80-$120/hour Location: Remote"
     *
     * We need to know where one label's value ends and the next
     * label begins. Matching is done against this known list rather
     * than "stop at the next uppercase letter", because real values
     * (e.g. "Contract", "Full Time", "Remote", "Bangalore") almost
     * always start with an uppercase letter themselves — a regex that
     * excludes uppercase from the captured value fails to capture
     * anything on virtually every real snippet.
     */
    protected const SNIPPET_LABELS = [
        'Employment Type',
        'Job Type',
        'Type',
        'Salary Range',
        'Compensation',
        'Salary',
        'Pay Range',
        'Pay',
        'Wage',
        'Remuneration',
        'CTC',
        'Rate',
        'Location',
        'Position',
        'Role',
    ];

    /**
     * Extract the value following a given label in labeled snippet
     * text, stopping at the next known label or end of string.
     *
     * $snippetText is expected to already be stripped of HTML tags.
     */
    protected function extractLabeledValue(
        string $snippetText,
        string $label
    ): ?string {
        $stopLabels = array_filter(
            self::SNIPPET_LABELS,
            fn ($candidate) => strcasecmp($candidate, $label) !== 0
        );

        $stopPattern = implode(
            '|',
            array_map(
                fn ($candidate) => preg_quote($candidate, '/'),
                $stopLabels
            )
        );

        $pattern = '/\b'
            . preg_quote($label, '/')
            . ':\s*(.*?)(?=\s*(?:' . $stopPattern . '):|$)/isu';

        if (preg_match($pattern, $snippetText, $matches)) {
            $value = trim($matches[1]);

            if ($value !== '') {
                return $value;
            }
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
     *    If found but doesn't mention "remote", trust it as an
     *    explicit non-remote location.
     * 2. Fallback keyword scan across title + snippet text.
     *
     * $snippetText is expected to already be stripped of HTML tags.
     */
    protected function extractRemoteSignal(
        ?string $title,
        string $snippetText
    ): bool {
        $locationValue = $this->extractLabeledValue(
            $snippetText,
            'Location'
        );

        if ($locationValue !== null) {
            return (bool) preg_match(
                '/\bremote\b/i',
                $locationValue
            );
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
     * Extract job type from a labeled type field in the snippet.
     *
     * Checks "Employment Type:", "Job Type:", and "Type:" in that
     * order, since more specific labels should win when present.
     *
     * Example:
     * "... Type: Contract Compensation: $80-$120/hour ..."
     *
     * $snippetText is expected to already be stripped of HTML tags.
     */
    protected function extractJobType(string $snippetText): ?string
    {
        $jobType = $this->extractLabeledValue(
            $snippetText,
            'Employment Type'
        )
            ?? $this->extractLabeledValue(
                $snippetText,
                'Job Type'
            )
            ?? $this->extractLabeledValue(
                $snippetText,
                'Type'
            );

        if ($jobType !== null) {
            return $jobType;
        }

        $location = $this->extractLabeledValue(
            $snippetText,
            'Location'
        );

        if (
            $location !== null &&
            preg_match(
                '/\b(remote|work from home|wfh|hybrid)\b/i',
                $location
            )
        ) {
            return $location;
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
            str_contains($normalized, 'fully remote'),
            str_contains($normalized, '100% remote'),
            str_contains($normalized, 'remote job'),
            str_contains($normalized, 'remote position'),
            str_contains($normalized, 'remote-first'),
            str_contains($normalized, 'telecommute'),
            str_contains($normalized, 'remote') => 'REMOTE',

            str_contains($normalized, 'work from home'),
            str_contains($normalized, 'wfh') => 'REMOTE',

            str_contains($normalized, 'hybrid') => 'HYBRID',

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
    protected function extractSalary(
        string $snippetText,
        string $titleText = ''
    ): ?array {
        $text = $snippetText . ' ' . $titleText;

        $text = html_entity_decode(
            preg_replace('/<[^>]+>/u', ' ', $text),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        $text = preg_replace('/\s+/u', ' ', $text);

        $text = trim($text);

        if ($text === '') {
            return null;
        }

        /*
         * WhatJobs commonly provides:
         *
         * Salary: ₹35,000–₹50,000/month
         * Salary: $70/hr
         * Salary: ₹50,000/month
         *
         * Extract only the value after "Salary:".
         */

        if (
            preg_match(
                '/Salary\s*:\s*(.*?)(?=Job\s*Type\s*:|Employment\s*Type\s*:|Location\s*:|Work\s*Mode\s*:|$)/iu',
                $text,
                $salaryMatch
            )
        ) {
            $salaryText = trim($salaryMatch[1]);
        } else {
            $salaryText = $text;
        }

        /*
         * Parse:
         *
         * ₹35,000–₹50,000/month
         * ₹35,000-₹50,000/month
         * ₹35,000 to ₹50,000/month
         * $70/hr
         * Upto $70/hr
         * $100,000-$150,000/year
         */

        if (
            !preg_match(
                '/
                    (?:upto|up\s*to|up-to|maximum|max)?\s*
                    ([₹$])\s*
                    ([\d,]+(?:\.\d+)?)
                    (?:
                        \s*(?:-|–|—|to)\s*
                        (?:[₹$])?\s*
                        ([\d,]+(?:\.\d+)?)
                    )?
                    \s*
                    (?:\/|\bper\s*)
                    (hour|hr|year|yr|annum|month|mo|week|wk|day)
                    \b
                /ixu',
                $salaryText,
                $matches
            )
        ) {
            return null;
        }

        $currency = match ($matches[1]) {
            '₹' => 'INR',
            '$' => 'USD',
            default => null,
        };

        if ($currency === null) {
            return null;
        }

        $min = (float) str_replace(',', '', $matches[2]);

        $max = !empty($matches[3])
            ? (float) str_replace(',', '', $matches[3])
            : $min;

        if ($min <= 0 || $max <= 0) {
            return null;
        }

        if ($max < $min) {
            [$min, $max] = [$max, $min];
        }

        $unitRaw = strtolower(trim($matches[4]));

        $unit = match (true) {
            in_array($unitRaw, ['hour', 'hr'], true) => 'HOUR',
            in_array($unitRaw, ['year', 'yr', 'annum'], true) => 'YEAR',
            in_array($unitRaw, ['month', 'mo'], true) => 'MONTH',
            in_array($unitRaw, ['week', 'wk'], true) => 'WEEK',
            $unitRaw === 'day' => 'DAY',
            default => null,
        };

        if ($unit === null) {
            return null;
        }

        return [
            'currency' => $currency,
            'min' => $min,
            'max' => $max,
            'unit' => $unit,
        ];
    }
}