<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class IndiaJobsCacheService
{
    protected string $cacheKeyPrefix = 'careerjet.india.jobs.page';

    protected string $metaCacheKey = 'careerjet.india.jobs.meta';

    protected int $cacheTtl = 86400; // (24 hours = 1 day)

    public function storePage(
        int $page,
        array $jobs
    ): void {
        Cache::put(
            $this->getPageCacheKey($page),
            $jobs,
            $this->cacheTtl
        );
    }

    public function getPage(
        int $page
    ): array {
        return Cache::get(
            $this->getPageCacheKey($page),
            []
        );
    }

    public function hasPage(
        int $page
    ): bool {
        return Cache::has(
            $this->getPageCacheKey($page)
        );
    }

    public function storeMeta(
        array $meta
    ): void {
        Cache::put(
            $this->metaCacheKey,
            $meta,
            $this->cacheTtl
        );
    }

    public function getMeta(): array
    {
        return Cache::get(
            $this->metaCacheKey,
            []
        );
    }

    public function hasMeta(): bool
    {
        return Cache::has(
            $this->metaCacheKey
        );
    }

    public function getPageCacheKey(
        int $page
    ): string {
        return $this->cacheKeyPrefix . '.' . $page;
    }

    public function getMetaCacheKey(): string
    {
        return $this->metaCacheKey;
    }

    public function clearPage(
        int $page
    ): void {
        Cache::forget(
            $this->getPageCacheKey($page)
        );
    }

    public function clear(): void
    {
        $meta = $this->getMeta();

        $totalPages = (int) (
            $meta['pages'] ?? 0
        );

        for (
            $page = 1;
            $page <= $totalPages;
            $page++
        ) {
            $this->clearPage($page);
        }

        Cache::forget(
            $this->metaCacheKey
        );
    }
}