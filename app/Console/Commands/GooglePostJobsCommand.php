<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Services\GoogleIndexing\GoogleIndexingService;
use Illuminate\Console\Command;
use Throwable;

class GooglePostJobsCommand extends Command
{
    protected $signature = 'google:post-jobs
                            {--limit=10 : Number of jobs to submit to Google}';

    protected $description = 'Submit active pending WhatJobs jobs to Google Indexing API';

    public function handle(
        GoogleIndexingService $googleIndexingService
    ): int {
        $limit = max(1, (int) $this->option('limit'));

        $this->info('Starting Google Indexing API job posting...');
        $this->line("Limit: {$limit}");
        $this->newLine();

        /*
         * Only process jobs where:
         *
         * provider = whatjobs
         * is_active = 1
         * job_gfj_status = 1
         */
        $jobs = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', 1)
            ->where('job_gfj_status', 1)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->orderBy('id')
            ->limit($limit)
            ->get([
                'id',
                'title',
                'slug',
                'is_active',
                'job_gfj_status',
            ]);

        $total = $jobs->count();

        if ($total === 0) {
            $this->info('No pending active WhatJobs jobs found.');
            $this->line('Required conditions:');
            $this->line('provider = whatjobs');
            $this->line('is_active = 1');
            $this->line('job_gfj_status = 1');

            return self::SUCCESS;
        }

        $this->info("Jobs found: {$total}");
        $this->newLine();

        $success = 0;
        $failed = 0;

        foreach ($jobs as $job) {
            $url = url('/viewjob/' . $job->slug);

            $this->line(
                "Job #{$job->id}: {$job->title}"
            );

            $this->line(
                "URL: {$url}"
            );

            try {
                $result = $googleIndexingService->update($url);

                if (($result['success'] ?? false) === true) {
                    /*
                     * Google accepted the URL notification.
                     *
                     * 1 = waiting to post
                     * 2 = failed
                     * 3 = successfully submitted
                     */
                    $job->job_gfj_status = 3;
                    $job->save();

                    $success++;

                    $this->info(
                        'SUCCESS → job_gfj_status = 3'
                    );
                } else {
                    /*
                     * Failure:
                     * 1 → 2
                     *
                     * No automatic retry.
                     */
                    $job->job_gfj_status = 2;
                    $job->save();

                    $failed++;

                    $this->error(
                        'FAILED → job_gfj_status = 2'
                    );

                    $this->line(
                        'HTTP status: '
                        . ($result['status'] ?? 'unknown')
                    );

                    if (! empty($result['error'])) {
                        $this->line(
                            'Error: ' . $result['error']
                        );
                    }

                    if (! empty($result['body'])) {
                        $this->line(
                            'Google response: ' . $result['body']
                        );
                    }
                }
            } catch (Throwable $e) {
                /*
                 * Unexpected exception:
                 * 1 → 2
                 *
                 * No automatic retry.
                 */
                $job->job_gfj_status = 2;
                $job->save();

                $failed++;

                $this->error(
                    'FAILED → job_gfj_status = 2'
                );

                $this->line(
                    'Error: ' . $e->getMessage()
                );
            }

            $this->newLine();
        }

        $this->info('Google Indexing API posting completed.');
        $this->newLine();

        $this->table(
            [
                'Requested',
                'Found',
                'Successful',
                'Failed',
            ],
            [
                [
                    $limit,
                    $total,
                    $success,
                    $failed,
                ],
            ]
        );

        return self::SUCCESS;
    }
}