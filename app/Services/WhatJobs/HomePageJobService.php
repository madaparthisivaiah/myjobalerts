<?php

namespace App\Services\WhatJobs;

use App\Models\Job;

use Illuminate\Support\Collection;

use Illuminate\Support\Str;

class HomePageJobService

{

    /**
     * Get all data required by the WhatJobs homepage.
     */
    public function getHomePageData(): array

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
     * Jobs grouped by state.
     */
    protected function jobsByState(Collection $jobs): Collection

    {

        return $this->groupLocationValues(

            $jobs,

            'state',

            12

        );

    }


    /**
     * Jobs grouped by city.
     */
    protected function jobsByCity(Collection $jobs): Collection

    {

        return $this->groupLocationValues(

            $jobs,

            'city',

            12

        );

    }


    /**
     * Jobs grouped by company.
     */
    protected function jobsByCompany(Collection $jobs): Collection

    {

        return $jobs

            ->filter(function ($job) {

                return filled($job->company);

            })

            ->groupBy(function ($job) {

                return $this->normaliseGroupValue($job->company);

            })

            ->map(function (Collection $companyJobs) {

                $company = $companyJobs

                    ->first()

                    ->company;

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


    protected function jobsByLocation(Collection $jobs): Collection

    {

        return $jobs

            ->filter(function ($job) {

                return filled($job->location);

            })

            ->groupBy(function ($job) {

                return $this->normaliseGroupValue($job->location);

            })

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
     * Group location values safely.
     *
     * Important:
     * We don't simply do:
     *
     * str_replace('-', ' ', $value)
     *
     * because names such as Hubli-Dharwad are valid
     * names and should remain unchanged.
     */
    protected function groupLocationValues(

        Collection $jobs,

        string $field,

        int $limit = 12

    ): Collection {

        return $jobs

            ->filter(function ($job) use ($field) {

                return filled($job->{$field});

            })

            ->groupBy(function ($job) use ($field) {

                return $this->normaliseGroupValue(

                    $job->{$field}

                );

            })

            ->map(function (Collection $locationJobs) use ($field) {

                $value = $locationJobs

                    ->first()

                    ->{$field};

                return [

                    'name' => $value,

                    'slug' => Str::slug($value),

                    'count' => $locationJobs->count(),

                ];

            })

            ->sortByDesc('count')

            ->take($limit)

            ->values();

    }


    /**
     * Normalise a value only for grouping.
     *
     * This is NOT used to create the displayed name.
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