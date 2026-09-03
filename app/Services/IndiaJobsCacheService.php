<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class IndiaJobsCacheService
{
    protected string $cacheKeyPrefix = 'careerjet.india.jobs.page';

    protected string $metaCacheKey = 'careerjet.india.jobs.meta';

    /*
     * Cache lifetime.
     *
     * 3600 = 1 hour
     *
     * The Artisan refresh command will update the cache
     * whenever it is executed.
     */
    protected int $cacheTtl = 3600;

    /**
     * Store jobs for a specific Careerjet page.
     */
    public function storePage(
        int $page,
        array $jobs
    ): void {
        Cache::put(
            $this->getPageCacheKey($page),
            array_values($jobs),
            $this->cacheTtl
        );
    }

    /**
     * Get jobs for a specific cached page.
     */
    public function getPage(
        int $page
    ): array {
        return Cache::get(
            $this->getPageCacheKey($page),
            []
        );
    }

    /**
     * Check whether a page exists in cache.
     */
    public function hasPage(
        int $page
    ): bool {
        return Cache::has(
            $this->getPageCacheKey($page)
        );
    }

    /**
     * Store cache metadata.
     */
    public function storeMeta(
        array $meta
    ): void {
        Cache::put(
            $this->metaCacheKey,
            $meta,
            $this->cacheTtl
        );
    }

    /**
     * Get cache metadata.
     */
    public function getMeta(): array
    {
        return Cache::get(
            $this->metaCacheKey,
            []
        );
    }

    /**
     * Check whether cache metadata exists.
     */
    public function hasMeta(): bool
    {
        return Cache::has(
            $this->metaCacheKey
        );
    }

    /**
     * Get cache key for a page.
     */
    public function getPageCacheKey(
        int $page
    ): string {
        return $this->cacheKeyPrefix . '.' . $page;
    }

    /**
     * Get metadata cache key.
     */
    public function getMetaCacheKey(): string
    {
        return $this->metaCacheKey;
    }

    /**
     * Clear a specific cached page.
     */
    public function clearPage(
        int $page
    ): void {
        Cache::forget(
            $this->getPageCacheKey($page)
        );
    }

    /**
     * Clear all Careerjet India jobs cache.
     */
    public function clear(): void
    {
        $meta = $this->getMeta();

        $totalPages = (int) (
            $meta['pages'] ?? 0
        );

        /*
         * Remove all previously cached pages.
         */
        for (
            $page = 1;
            $page <= $totalPages;
            $page++
        ) {
            $this->clearPage($page);
        }

        /*
         * Remove metadata.
         */
        Cache::forget(
            $this->metaCacheKey
        );
    }

    /**
     * Start a completely fresh cache refresh.
     *
     * This removes the previous cache before the
     * Artisan command starts storing new pages.
     */
    public function startRefresh(): void
    {
        $this->clear();
    }

    /**
     * Check whether the cache contains usable jobs.
     */
    public function hasJobs(): bool
    {
        $meta = $this->getMeta();

        $cachedPages = (int) (
            $meta['cached_pages'] ?? 0
        );

        if ($cachedPages <= 0) {
            return false;
        }

        for (
            $page = 1;
            $page <= $cachedPages;
            $page++
        ) {
            if ($this->hasPage($page)) {
                return true;
            }
        }

        return false;
    }
}