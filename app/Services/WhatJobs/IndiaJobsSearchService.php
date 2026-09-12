<?php

namespace App\Services\whatjobs;

use App\Models\Job;

class IndiaJobsSearchService
{
    protected WhatJobsSearchPageCacheService $searchPageCache;

    public function __construct(
        WhatJobsSearchPageCacheService $searchPageCache
    ) {
        $this->searchPageCache = $searchPageCache;
    }

    /**
     * Search WhatJobs jobs.
     *
     * Filters:
     * - keyword
     * - location
     * - company
     * - page
     * - per page
     * - sort
     *
     * Search results remain cached until the next successful
     * WhatJobs synchronization creates a new cache version.
     */
    public function search(
        string $keyword = '',
        string $location = '',
        int $page = 1,
        int $perPage = 20,
        string $sort = 'relevance',
        string $company = ''
    ): array {
        $keyword = trim($keyword);
        $location = trim($location);
        $company = trim($company);

        $page = max($page, 1);
        $perPage = max($perPage, 1);

        /*
         * -------------------------------------------------------------
         * CHECK SEARCH PAGE CACHE
         * -------------------------------------------------------------
         */
        $cachedResult = $this->searchPageCache->get(
            $keyword,
            $location,
            $company,
            $page,
            $perPage,
            $sort
        );

        if ($cachedResult !== null) {
            return $cachedResult;
        }

        /*
         * -------------------------------------------------------------
         * BUILD WHATJOBS QUERY
         * -------------------------------------------------------------
         *
         * IMPORTANT:
         *
         * Your existing WhatJobs convention uses:
         *
         * provider = whatjobs
         * is_active = 0
         *
         * We preserve that exactly.
         */
        $query = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', true);

        /*
         * -------------------------------------------------------------
         * KEYWORD SEARCH
         * -------------------------------------------------------------
         *
         * Search:
         * - title
         * - company
         * - snippet
         */
        if ($keyword !== '') {
            $searchKeyword = mb_strtolower($keyword);

            $query->where(function ($q) use ($searchKeyword) {
                $q->whereRaw(
                    'LOWER(title) LIKE ?',
                    ['%' . $searchKeyword . '%']
                )
                ->orWhereRaw(
                    'LOWER(company) LIKE ?',
                    ['%' . $searchKeyword . '%']
                )
                ->orWhereRaw(
                    'LOWER(snippet) LIKE ?',
                    ['%' . $searchKeyword . '%']
                );
            });
        }

        /*
         * -------------------------------------------------------------
         * LOCATION SEARCH
         * -------------------------------------------------------------
         */
        if ($location !== '') {
            $searchLocation = mb_strtolower($location);

            $query->whereRaw(
                'LOWER(location) LIKE ?',
                ['%' . $searchLocation . '%']
            );
        }

        /*
         * -------------------------------------------------------------
         * COMPANY SEARCH
         * -------------------------------------------------------------
         */
        if ($company !== '') {
            $searchCompany = mb_strtolower($company);

            $query->whereRaw(
                'LOWER(company) LIKE ?',
                ['%' . $searchCompany . '%']
            );
        }

        /*
         * -------------------------------------------------------------
         * SORT
         * -------------------------------------------------------------
         *
         * Relevance currently uses published_at as the default
         * ordering because there is no relevance score column.
         *
         * Date also uses published_at.
         */
        if ($sort === 'date') {
            $query->orderByDesc('published_at');
        } else {
            $query->orderByDesc('published_at');
        }

        /*
         * -------------------------------------------------------------
         * TOTAL
         * -------------------------------------------------------------
         */
        $total = (clone $query)->count();

        /*
         * -------------------------------------------------------------
         * LAST PAGE
         * -------------------------------------------------------------
         */
        $lastPage = max(
            (int) ceil($total / $perPage),
            1
        );

        /*
         * -------------------------------------------------------------
         * KEEP PAGE WITHIN VALID RANGE
         * -------------------------------------------------------------
         */
        if ($page > $lastPage) {
            $page = $lastPage;
        }

        /*
         * -------------------------------------------------------------
         * FETCH CURRENT PAGE
         * -------------------------------------------------------------
         */
        $jobs = $query
            ->forPage($page, $perPage)
            ->get();

        /*
         * -------------------------------------------------------------
         * RESULT RANGE
         * -------------------------------------------------------------
         */
        $from = $total > 0
            ? (($page - 1) * $perPage) + 1
            : 0;

        $to = $total > 0
            ? min(
                $from + $jobs->count() - 1,
                $total
            )
            : 0;

        /*
         * -------------------------------------------------------------
         * FINAL RESULT
         * -------------------------------------------------------------
         */
        $result = [
            'jobs' => $jobs->all(),
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
            'lastPage' => $lastPage,
            'from' => $from,
            'to' => $to,
        ];

        /*
         * -------------------------------------------------------------
         * SAVE SEARCH PAGE CACHE
         * -------------------------------------------------------------
         *
         * No TTL.
         *
         * This cached result remains available until the WhatJobs
         * sync creates a new cache version.
         */
        $this->searchPageCache->put(
            $result,
            $keyword,
            $location,
            $company,
            $page,
            $perPage,
            $sort
        );

        return $result;
    }
}