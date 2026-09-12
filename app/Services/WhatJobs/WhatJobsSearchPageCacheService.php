<?php

namespace App\Services\whatjobs;

use Illuminate\Support\Facades\Cache;

class WhatJobsSearchPageCacheService
{
    /**
     * Cache version key.
     *
     * This version changes only after a successful
     * WhatJobs synchronization.
     */
    protected string $versionKey = 'whatjobs.search.cache.version';

    /**
     * Get the current cache version.
     */
    public function version(): string
    {
        return Cache::get(
            $this->versionKey,
            'initial'
        );
    }

    /**
     * Generate a unique cache key for a search page.
     *
     * Cache depends on:
     * - keyword
     * - location
     * - company
     * - page
     * - per page
     * - sort
     */
    public function key(
        string $keyword = '',
        string $location = '',
        string $company = '',
        int $page = 1,
        int $perPage = 20,
        string $sort = 'relevance'
    ): string {
        $keyword = mb_strtolower(
            trim($keyword)
        );

        $location = mb_strtolower(
            trim($location)
        );

        $company = mb_strtolower(
            trim($company)
        );

        $sort = mb_strtolower(
            trim($sort)
        );

        $page = max($page, 1);

        $perPage = max($perPage, 1);

        return 'whatjobs.search.page:'
            . $this->version()
            . ':'
            . md5(
                $keyword . '|'
                . $location . '|'
                . $company . '|'
                . $page . '|'
                . $perPage . '|'
                . $sort
            );
    }

    /**
     * Get a cached search result.
     */
    public function get(
        string $keyword = '',
        string $location = '',
        string $company = '',
        int $page = 1,
        int $perPage = 20,
        string $sort = 'relevance'
    ): ?array {
        $key = $this->key(
            $keyword,
            $location,
            $company,
            $page,
            $perPage,
            $sort
        );

        $result = Cache::get($key);

        return is_array($result)
            ? $result
            : null;
    }

    /**
     * Store a search result permanently.
     *
     * It remains valid until the cache version changes
     * after a successful WhatJobs sync.
     */
    public function put(
        array $result,
        string $keyword = '',
        string $location = '',
        string $company = '',
        int $page = 1,
        int $perPage = 20,
        string $sort = 'relevance'
    ): void {
        $key = $this->key(
            $keyword,
            $location,
            $company,
            $page,
            $perPage,
            $sort
        );

        Cache::forever(
            $key,
            $result
        );
    }

    /**
     * Create a new cache version.
     *
     * IMPORTANT:
     * Call this ONLY after a complete and successful
     * WhatJobs sync.
     */
    public function newVersion(): string
    {
        $version = now()->format(
            'YmdHis'
        );

        Cache::forever(
            $this->versionKey,
            $version
        );

        return $version;
    }

    /**
     * Get the version cache key.
     */
    public function getVersionKey(): string
    {
        return $this->versionKey;
    }

    /**
     * Check whether a cached search result exists.
     */
    public function has(
        string $keyword = '',
        string $location = '',
        string $company = '',
        int $page = 1,
        int $perPage = 20,
        string $sort = 'relevance'
    ): bool {
        return Cache::has(
            $this->key(
                $keyword,
                $location,
                $company,
                $page,
                $perPage,
                $sort
            )
        );
    }
}