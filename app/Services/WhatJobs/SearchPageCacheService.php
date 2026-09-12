<?php

namespace App\Services\whatjobs;

use Illuminate\Support\Facades\Cache;

class SearchPageCacheService
{
    /**
     * Cache version.
     *
     * This changes only after a successful WhatJobs sync.
     */
    private const VERSION_KEY = 'whatjobs_search_cache_version';

    /**
     * Get the current cache version.
     */
    public function version(): string
    {
        return Cache::get(self::VERSION_KEY, 'initial');
    }

    /**
     * Generate a unique cache key for a WhatJobs search page.
     *
     * Cache depends on:
     * - keyword
     * - location
     * - company
     * - page
     */
    public function key(
        string $keyword,
        string $location,
        string $company,
        int $page
    ): string {
        $keyword = mb_strtolower(trim($keyword));
        $location = mb_strtolower(trim($location));
        $company = mb_strtolower(trim($company));

        return 'whatjobs_search:'
            . $this->version()
            . ':'
            . md5(
                $keyword . '|'
                . $location . '|'
                . $company . '|'
                . $page
            );
    }

    /**
     * Generate a new cache version.
     *
     * Call this ONLY after a successful WhatJobs sync.
     */
    public function newVersion(): void
    {
        Cache::forever(
            self::VERSION_KEY,
            now()->format('YmdHis')
        );
    }
}