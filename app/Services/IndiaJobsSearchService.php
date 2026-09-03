<?php

namespace App\Services;

class IndiaJobsSearchService
{
    protected IndiaJobsCacheService $indiaJobsCache;

    public function __construct(
        IndiaJobsCacheService $indiaJobsCache
    ) {
        $this->indiaJobsCache = $indiaJobsCache;
    }

    public function search(
        string $keyword = '',
        string $location = '',
        int $page = 1,
        int $perPage = 20,
        string $sort = 'relevance'
    ): array {
        $keyword = trim($keyword);
        $location = trim($location);

        $page = max($page, 1);
        $perPage = max($perPage, 1);

        $meta = $this->indiaJobsCache->getMeta();

        $cachedPages = (int) ($meta['cached_pages'] ?? 0);

        if ($cachedPages <= 0) {
            $cachedPages = (int) ($meta['pages'] ?? 0);
        }

        if ($cachedPages <= 0) {
            return [
                'jobs' => [],
                'total' => 0,
                'perPage' => $perPage,
                'currentPage' => $page,
                'lastPage' => 1,
                'from' => 0,
                'to' => 0,
                'cachedPages' => 0,
                'cachedJobs' => 0,
            ];
        }

        $matchingJobs = [];

        for (
            $cachedPage = 1;
            $cachedPage <= $cachedPages;
            $cachedPage++
        ) {
            $jobs = $this->indiaJobsCache->getPage(
                $cachedPage
            );

            if (empty($jobs)) {
                continue;
            }

            foreach ($jobs as $job) {
                if (!$this->matchesKeyword($job, $keyword)) {
                    continue;
                }

                if (!$this->matchesLocation($job, $location)) {
                    continue;
                }

                $matchingJobs[] = $job;
            }
        }

        $matchingJobs = $this->sortJobs(
            $matchingJobs,
            $sort
        );

        $total = count($matchingJobs);

        $lastPage = max(
            (int) ceil($total / $perPage),
            1
        );

        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $offset = ($page - 1) * $perPage;

        $paginatedJobs = array_slice(
            $matchingJobs,
            $offset,
            $perPage
        );

        $from = $total > 0
            ? $offset + 1
            : 0;

        $to = $total > 0
            ? min(
                $offset + count($paginatedJobs),
                $total
            )
            : 0;

        return [
            'jobs' => array_values($paginatedJobs),
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'lastPage' => $lastPage,
            'from' => $from,
            'to' => $to,
            'cachedPages' => $cachedPages,
            'cachedJobs' => (int) (
                $meta['cached_jobs'] ?? 0
            ),
        ];
    }

    protected function matchesKeyword(
        array $job,
        string $keyword
    ): bool {
        if ($keyword === '') {
            return true;
        }

        $searchKeyword = mb_strtolower($keyword);

        $title = mb_strtolower(
            (string) ($job['title'] ?? '')
        );

        $company = mb_strtolower(
            (string) ($job['company'] ?? '')
        );

        $description = mb_strtolower(
            strip_tags(
                (string) ($job['description'] ?? '')
            )
        );

        return str_contains(
            $title,
            $searchKeyword
        )
        || str_contains(
            $company,
            $searchKeyword
        )
        || str_contains(
            $description,
            $searchKeyword
        );
    }

    protected function matchesLocation(
        array $job,
        string $location
    ): bool {
        if ($location === '') {
            return true;
        }

        $searchLocation = mb_strtolower($location);

        $jobLocations = mb_strtolower(
            (string) ($job['locations'] ?? '')
        );

        return str_contains(
            $jobLocations,
            $searchLocation
        );
    }

    protected function sortJobs(
        array $jobs,
        string $sort
    ): array {
        if ($sort === 'date') {
            usort(
                $jobs,
                function ($a, $b) {
                    $dateA = strtotime(
                        (string) ($a['date'] ?? '')
                    );

                    $dateB = strtotime(
                        (string) ($b['date'] ?? '')
                    );

                    return $dateB <=> $dateA;
                }
            );
        }

        return $jobs;
    }
}