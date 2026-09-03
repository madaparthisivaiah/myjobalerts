<?php

namespace App\Console\Commands;

use App\Services\CareerjetService;
use App\Services\IndiaJobsCacheService;
use Illuminate\Console\Command;

class RefreshIndiaJobs extends Command
{
    protected $signature = 'careerjet:refresh-india-jobs
                            {--pages= : Number of pages to refresh for testing}';

    protected $description = 'Fetch India jobs from Careerjet and update the jobs cache';

    public function handle(
        CareerjetService $careerjet,
        IndiaJobsCacheService $indiaJobsCache
    ): int {
        $this->info('Starting India jobs cache refresh...');

        $pageSize = 200;

        try {
            /*
             * First request:
             * Get the total number of jobs/pages from Careerjet.
             */
            $this->info('Fetching first page from Careerjet...');

            $result = $careerjet->search([
                'location' => 'India',
                'page' => 1,
                'page_size' => $pageSize,
                'sort' => 'date',
            ]);

            if (($result['type'] ?? '') === 'ERROR') {
                $this->error(
                    $result['message'] ?? 'Careerjet API error.'
                );

                return self::FAILURE;
            }

            $totalPages = (int) ($result['pages'] ?? 0);
            $totalHits = (int) ($result['hits'] ?? 0);

            if ($totalPages <= 0) {
                $this->warn('Careerjet returned no pages.');

                return self::FAILURE;
            }

            /*
             * Check --pages option.
             *
             * Example:
             * --pages=2
             */
            $requestedPages = (int) $this->option('pages');

            if ($requestedPages > 0) {
                $pagesToFetch = min(
                    $requestedPages,
                    $totalPages
                );

                $this->warn(
                    "TEST MODE: Fetching only {$pagesToFetch} of {$totalPages} pages."
                );
            } else {
                $pagesToFetch = $totalPages;

                $this->warn(
                    "FULL MODE: Fetching all {$totalPages} pages."
                );
            }

            $this->info("Total jobs reported by Careerjet: {$totalHits}");
            $this->info("Total pages reported by Careerjet: {$totalPages}");
            $this->info("Pages to fetch: {$pagesToFetch}");

            $totalJobs = 0;

            /*
             * Fetch pages.
             */
            for ($page = 1; $page <= $pagesToFetch; $page++) {

                /*
                 * We already fetched page 1 above.
                 * No need to call Careerjet again for page 1.
                 */
                if ($page === 1) {
                    $jobs = $result['jobs'] ?? [];
                } else {
                    $result = $careerjet->search([
                        'location' => 'India',
                        'page' => $page,
                        'page_size' => $pageSize,
                        'sort' => 'date',
                    ]);

                    if (($result['type'] ?? '') === 'ERROR') {
                        $this->error(
                            "Failed while fetching page {$page}."
                        );

                        $this->error(
                            $result['message'] ?? 'Careerjet API error.'
                        );

                        return self::FAILURE;
                    }

                    $jobs = $result['jobs'] ?? [];
                }

                /*
                 * Store this page in cache.
                 */
                $indiaJobsCache->storePage(
                    $page,
                    $jobs
                );

                $jobCount = count($jobs);

                $totalJobs += $jobCount;

                $this->info(
                    "Page {$page}/{$pagesToFetch} cached ({$jobCount} jobs)"
                );
            }

            /*
             * Store cache metadata.
             */
            $isComplete = $pagesToFetch >= $totalPages;

            $indiaJobsCache->storeMeta([
                'hits' => $totalHits,
                'pages' => $totalPages,
                'cached_pages' => $pagesToFetch,
                'page_size' => $pageSize,
                'cached_jobs' => $totalJobs,
                'complete' => $isComplete,
                'updated_at' => now()->toDateTimeString(),
            ]);

            $this->newLine();

            $this->info('India jobs cache refresh completed.');

            $this->info("Careerjet jobs: {$totalHits}");
            $this->info("Careerjet pages: {$totalPages}");
            $this->info("Pages cached: {$pagesToFetch}");
            $this->info("Jobs cached: {$totalJobs}");

            if (!$isComplete) {
                $this->warn(
                    'This is a partial cache because --pages was used.'
                );
            }

            return self::SUCCESS;

        } catch (\Throwable $e) {
            report($e);

            $this->error(
                'Failed to refresh India jobs cache.'
            );

            $this->error(
                $e->getMessage()
            );

            return self::FAILURE;
        }
    }
}