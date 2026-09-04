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
    public function getTopCompanies(
        string $city = ''
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

        foreach ($companies as $company => $count) {
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

    /**
     * Get all companies from cached jobs.
     *
     * Optional city filter.
     * No limit.
     */
    public function getCompanies(string $city = ''): array
    {
        $city = trim($city);

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
            $pageJobs = $this->indiaJobsCache->getPage($page);

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

        foreach ($companies as $company => $count) {
            $result[] = [
                'name' => $company,
                'jobs' => $count,
            ];
        }

        return $result;
    }

    /**
     * Get all unique locations from cached jobs.
     *
     * No limit.
     */
    public function getLocations(): array
    {
        $locations = [];

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
            $pageJobs = $this->indiaJobsCache->getPage($page);
            dd($pageJobs);

            if (empty($pageJobs)) {
                continue;
            }

            foreach ($pageJobs as $job) {

                $location = trim(
                    (string) ($job['locations'] ?? '')
                );

                if ($location === '') {
                    continue;
                }

                $key = mb_strtolower($location);

                if (!isset($locations[$key])) {
                    $locations[$key] = $location;
                }
            }
        }

        natcasesort($locations);

        return array_values($locations);
    }

    /**
     * Get all unique location values from cached jobs.
     *
     * Values are separated by comma.
     * No limit.
     */
    public function alllocations(): array
    {
        $cities = [];
        $states = [];

        $meta = $this->indiaJobsCache->getMeta();

        $cachedPages = (int) (
            $meta['cached_pages'] ?? 0
        );

        if ($cachedPages <= 0) {
            return [
                [],
                [],
            ];
        }

        for (
            $page = 1;
            $page <= $cachedPages;
            $page++
        ) {
            $pageJobs = $this->indiaJobsCache->getPage($page);

            if (empty($pageJobs)) {
                continue;
            }

            foreach ($pageJobs as $job) {

                $jobLocation = trim(
                    (string) ($job['locations'] ?? '')
                );

                if ($jobLocation === '') {
                    continue;
                }

                /*
                * Handle multiple locations.
                *
                * Example:
                * Aundh, Maharashtra - Pune, Maharashtra
                *
                * becomes:
                * Aundh, Maharashtra
                * Pune, Maharashtra
                */
                $locationGroups = preg_split(
                    '/\s+-\s+/',
                    $jobLocation
                );

                foreach ($locationGroups as $locationGroup) {

                    $parts = array_map(
                        'trim',
                        explode(',', $locationGroup)
                    );

                    $city = $parts[0] ?? '';
                    $state = $parts[1] ?? '';

                    /*
                    * Count city.
                    */
                    if ($city !== '') {

                        $cityKey = mb_strtolower($city);

                        if (!isset($cities[$cityKey])) {
                            $cities[$cityKey] = [
                                'name' => $city,
                                'jobs' => 0,
                            ];
                        }

                        $cities[$cityKey]['jobs']++;
                    }

                    /*
                    * Count state.
                    */
                    if ($state !== '') {

                        $stateKey = mb_strtolower($state);

                        if (!isset($states[$stateKey])) {
                            $states[$stateKey] = [
                                'name' => $state,
                                'jobs' => 0,
                            ];
                        }

                        $states[$stateKey]['jobs']++;
                    }
                }
            }
        }

        /*
        * Sort by job count, highest first.
        */
        uasort($cities, function ($a, $b) {
            return $b['jobs'] <=> $a['jobs'];
        });

        uasort($states, function ($a, $b) {
            return $b['jobs'] <=> $a['jobs'];
        });

        /*
        * Reset indexes to:
        * 0, 1, 2, 3...
        */
        $cities = array_values($cities);
        $states = array_values($states);

        return [
            $cities,
            $states,
        ];
    }
}