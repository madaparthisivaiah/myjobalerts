<?php

namespace App\Services\WhatJobs;

use App\Models\Job;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomePageJobService
{
    protected string $cacheKey = 'whatjobs:homepage';

    // 2 hours. WhatJobs sync runs every 2 hours.
    protected int $cacheTtl = 7200;

    /**
     * Get all data required by the WhatJobs homepage.
     */
    public function getHomePageData(): array
    {
        return Cache::remember(
            $this->cacheKey,
            $this->cacheTtl,
            function () {
                return $this->buildHomePageData();
            }
        );
    }

    /**
     * Refresh homepage cache after WhatJobs sync.
     */
    public function refreshHomepageCache(): void
    {
        Cache::put(
            $this->cacheKey,
            $this->buildHomePageData(),
            $this->cacheTtl
        );
    }

    /**
     * Build homepage data.
     */
    protected function buildHomePageData(): array
    {
        return [
            'latestJobs' => $this->latestJobs(),
            'locations' => $this->jobsByLocation(),
            'companies' => $this->jobsByCompany(),
            'totalJobs' => $this->totalJobsCount(),
        ];
    }

    /**
     * Base query for active WhatJobs jobs only.
     *
     * is_active = 1 means active.
     *
     * NOTE: never call ->get() on this directly for homepage
     * purposes -- the table has tens of thousands of rows and
     * loading them all as Eloquent models is what was causing
     * the memory errors. Always add limit()/aggregation before
     * hitting the database.
     */
    protected function activeWhatJobsQuery()
    {
        return Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', 1);
    }

    /**
     * Latest jobs. Only fetches 12 rows from the database.
     */
    protected function latestJobs(): Collection
    {
        return $this->activeWhatJobsQuery()
            ->orderByDesc(DB::raw('COALESCE(published_at, created_at)'))
            ->limit(12)
            ->get();
    }

    /**
     * Jobs grouped by company. Aggregated in the database instead
     * of loading every job row into memory and grouping in PHP.
     */
    protected function jobsByCompany(): Collection
    {
        return $this->activeWhatJobsQuery()
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->select(
                DB::raw('MIN(company) as company'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('LOWER(TRIM(REGEXP_REPLACE(company, "[[:space:]]+", " ")))'))
            ->orderByDesc('count')
            ->limit(12)
            ->get()
            ->map(function ($row) {
                return [
                    'name' => $row->company,
                    'slug' => Str::slug($row->company),
                    'count' => (int) $row->count,
                ];
            })
            ->values();
    }

    /**
     * Jobs grouped by location. Aggregated in the database instead
     * of loading every job row into memory and grouping in PHP.
     */
    protected function jobsByLocation(): Collection
    {
        return $this->activeWhatJobsQuery()
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->select(
                DB::raw('MIN(location) as location'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('LOWER(TRIM(REGEXP_REPLACE(location, "[[:space:]]+", " ")))'))
            ->orderByDesc('count')
            ->limit(12)
            ->get()
            ->map(function ($row) {
                return [
                    'name' => $row->location,
                    'slug' => Str::slug($row->location),
                    'count' => (int) $row->count,
                ];
            })
            ->values();
    }

    /**
     * Total active job count. A single COUNT(*) query instead of
     * loading every row to call ->count() on a collection.
     */
    protected function totalJobsCount(): int
    {
        return $this->activeWhatJobsQuery()->count();
    }
}