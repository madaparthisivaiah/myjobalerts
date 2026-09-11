<?php

namespace App\Services\WhatJobs;

use App\Models\Job;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class HomePageJobService
{
    protected string $cacheKey = 'whatjobs:homepage';

    // 7 hours. WhatJobs sync runs every 6 hours.
    protected int $cacheTtl = 25200;

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
        $jobs = $this->getActiveWhatJobs();

        return [
            'latestJobs' => $this->latestJobs($jobs),
            'locations' => $this->jobsByLocation($jobs),
            'companies' => $this->jobsByCompany($jobs),
            'totalJobs' => $jobs->count(),
        ];
    }

    /**
     * Get active WhatJobs jobs only.
     *
     * is_active = 0 means active.
     */
    protected function getActiveWhatJobs(): Collection
    {
        return Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', 0)
            ->get();
    }

    /**
     * Latest jobs.
     */
    protected function latestJobs(Collection $jobs): Collection
    {
        return $jobs
            ->sortByDesc(function ($job) {
                return $job->published_at
                    ?? $job->created_at
                    ?? now();
            })
            ->take(12)
            ->values();
    }

    /**
     * Jobs grouped by company.
     */
    protected function jobsByCompany(Collection $jobs): Collection
    {
        return $jobs
            ->filter(fn ($job) => filled($job->company))
            ->groupBy(fn ($job) => $this->normaliseGroupValue($job->company))
            ->map(function (Collection $companyJobs) {
                $company = $companyJobs->first()->company;

                return [
                    'name' => $company,
                    'slug' => Str::slug($company),
                    'count' => $companyJobs->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(12)
            ->values();
    }

    /**
     * Jobs grouped by location.
     */
    protected function jobsByLocation(Collection $jobs): Collection
    {
        return $jobs
            ->filter(fn ($job) => filled($job->location))
            ->groupBy(fn ($job) => $this->normaliseGroupValue($job->location))
            ->map(function (Collection $locationJobs) {
                $location = $locationJobs->first()->location;

                return [
                    'name' => $location,
                    'slug' => Str::slug($location),
                    'count' => $locationJobs->count(),
                ];
            })
            ->sortByDesc('count')
            ->take(12)
            ->values();
    }

    /**
     * Normalise value only for grouping.
     */
    protected function normaliseGroupValue(?string $value): string
    {
        return Str::lower(
            preg_replace(
                '/\s+/',
                ' ',
                trim((string) $value)
            )
        );
    }
}