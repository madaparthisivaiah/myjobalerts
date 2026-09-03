<?php

namespace App\Services;

class HomePageJobService
{
    protected IndiaJobsCacheService $indiaJobsCache;

    public function __construct(IndiaJobsCacheService $indiaJobsCache) {
        $this->indiaJobsCache = $indiaJobsCache;
    }

    /**
     * Get homepage jobs and top hiring companies.
     */
    public function getHomePageData(
        string $city = '',
        int $jobLimit = 6,
        int $companyLimit = 4
    ): array {
        $city = trim($city);

        $jobs = $this->getJobsForCity(
            $city,
            $jobLimit
        );

        /*
         * If we cannot find enough jobs for the
         * user's city, use India-wide jobs.
         */
        if (count($jobs) < $jobLimit) {
            $jobs = $this->getIndiaJobs($jobLimit);
        }

        $companies = $this->getTopCompanies(
            $city,
            $companyLimit
        );

        /*
         * If there are no companies for the city,
         * fall back to India-wide companies.
         */
        if (empty($companies) && $city !== '') {
            $companies = $this->getTopCompanies(
                '',
                $companyLimit
            );
        }

        return [
            'jobs' => $jobs,
            'companies' => $companies,
            'city' => $city,
        ];
    }

    /**
     * Get jobs matching a city.
     */
    protected function getJobsForCity(
        string $city,
        int $limit
    ): array {
        if ($city === '') {
            return [];
        }

        $jobs = [];

        $meta = $this->indiaJobsCache->getMeta();

        $cachedPages = (int) (
            $meta['cached_pages'] ?? 0
        );

        if ($cachedPages <= 0) {
            return [];
        }

        for (
            $page = 1;
            $page <= $cachedPages;
            $page++
        ) {
            $pageJobs = $this->indiaJobsCache->getPage(
                $page
            );

            if (empty($pageJobs)) {
                continue;
            }

            foreach ($pageJobs as $job) {
                if (!$this->matchesCity($job, $city)) {
                    continue;
                }

                $jobs[] = $job;

                if (count($jobs) >= $limit) {
                    return $jobs;
                }
            }
        }

        return $jobs;
    }

    /**
     * Get India-wide jobs.
     */
    protected function getIndiaJobs(
        int $limit
    ): array {
        $jobs = [];

        $meta = $this->indiaJobsCache->getMeta();

        $cachedPages = (int) (
            $meta['cached_pages'] ?? 0
        );

        if ($cachedPages <= 0) {
            return [];
        }

        for (
            $page = 1;
            $page <= $cachedPages;
            $page++
        ) {
            $pageJobs = $this->indiaJobsCache->getPage(
                $page
            );

            if (empty($pageJobs)) {
                continue;
            }

            foreach ($pageJobs as $job) {
                $jobs[] = $job;

                if (count($jobs) >= $limit) {
                    return $jobs;
                }
            }
        }

        return $jobs;
    }

    /**
     * Get companies with the highest number
     * of jobs for a city or India.
     */
    protected function getTopCompanies(
        string $city = '',
        int $limit = 4
    ): array {
        $companies = [];

        $meta = $this->indiaJobsCache->getMeta();

        $cachedPages = (int) (
            $meta['cached_pages'] ?? 0
        );

        if ($cachedPages <= 0) {
            return [];
        }

        for (
            $page = 1;
            $page <= $cachedPages;
            $page++
        ) {
            $pageJobs = $this->indiaJobsCache->getPage(
                $page
            );

            if (empty($pageJobs)) {
                continue;
            }

            foreach ($pageJobs as $job) {
                if (
                    $city !== ''
                    && !$this->matchesCity($job, $city)
                ) {
                    continue;
                }

                $company = trim(
                    (string) ($job['company'] ?? '')
                );

                if ($company === '') {
                    continue;
                }

                if (!isset($companies[$company])) {
                    $companies[$company] = 0;
                }

                $companies[$company]++;
            }
        }

        arsort($companies);

        $result = [];

        foreach (
            array_slice(
                $companies,
                0,
                $limit,
                true
            ) as $company => $count
        ) {
            $result[] = [
                'name' => $company,
                'jobs' => $count,
            ];
        }

        return $result;
    }

    /**
     * Check whether a job belongs to a city.
     */
    protected function matchesCity(
        array $job,
        string $city
    ): bool {
        $city = mb_strtolower(
            trim($city)
        );

        if ($city === '') {
            return false;
        }

        $locations = mb_strtolower(
            (string) ($job['locations'] ?? '')
        );

        return str_contains(
            $locations,
            $city
        );
    }
}