<?php

namespace App\Services;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class WhatJobsSyncService
{
    public function __construct(
        protected WhatJobsService $whatJobs
    ) {
    }

    /**
     * Sync all India jobs from WhatJobs.
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
        | We do this ONLY after every page has been processed.
        |
        | If WhatJobs API fails halfway through, old jobs are NOT
        | accidentally deactivated.
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
                'postcode' => $jobData['postcode'] ?? null,
                'job_type' => $jobData['job_type'] ?? null,
                'salary' => $jobData['salary'] ?? null,

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
            | Do NOT change job_gfj_status here.
            |
            | If the job already has status 2, it must remain 2.
            |
            */

            if ($job) {

                /*
                |--------------------------------------------------------------------------
                | If a previously expired job comes back
                |--------------------------------------------------------------------------
                |
                | Status 3 means the previous Google lifecycle was completed.
                |
                | Therefore, if WhatJobs sends this job again, start a new
                | Google posting lifecycle with status 1.
                |
                */

                if (
                    !$job->is_active &&
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
            | job_gfj_status = 1
            |
            | Meaning:
            | Waiting to be processed for Google for Jobs.
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
}