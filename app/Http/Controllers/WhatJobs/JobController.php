<?php

namespace App\Http\Controllers\WhatJobs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    /**
     * Display WhatJobs listings.
     */
    public function index(Request $request, ?string $value = null)
    {
        if ($value) {
            if ($request->routeIs('jobs.location')) {
                $request->merge([
                    'location' => $value,
                ]);
            }

            if ($request->routeIs('jobs.company')) {
                $companyMap = [
                'mufg-global-service-mgs' => 'MUFG Global Service',
                'artech-llc'              => 'Artech L.L.C.', 
                // add more here
            ];

            $company = $companyMap[$value] ?? str_replace('-', ' ', $value);

            $request->merge([
                'company' => $company,
            ]);
        }
    }

        $query = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', true);

        /*
        |--------------------------------------------------------------------------
        | Keyword
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {
            $keyword = trim($request->keyword);

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('company', 'like', "%{$keyword}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Location
        |--------------------------------------------------------------------------
        */

        if ($request->filled('location')) {
            $location = trim($request->location);

            $query->where('location', 'like', "%{$location}%");
        }

        /*
        |--------------------------------------------------------------------------
        | Company
        |--------------------------------------------------------------------------
        */

        if ($request->filled('company')) {
            $company = trim($request->company);

            $normalizedCompany = preg_replace(
                '/[^a-z0-9]/',
                '',
                strtolower($company)
            );

            $query->whereRaw(
                "LOWER(REPLACE(REPLACE(REPLACE(company, '.', ''), ' ', ''), '-', '')) LIKE ?",
                ["%{$normalizedCompany}%"]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $sort = $request->get('sort', 'latest');

        if ($sort === 'oldest') {
            $query->orderBy('published_at', 'asc');
        } else {
            $query->orderByDesc('published_at');
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $jobs = $query
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Sidebar companies
        |--------------------------------------------------------------------------
        */

        $companies = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', true)
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->select('company')
            ->distinct()
            ->orderBy('company')
            ->limit(20)
            ->pluck('company');

        return view('whatjobs.jobs.index', [
            'jobs' => $jobs,
            'companies' => $companies,
            'keyword' => $request->keyword,
            'location' => $request->location,
            'company' => $request->company,
            'sort' => $sort,
        ]);
    }

    /**
     * Display a single WhatJobs job.
     */
    public function show(string $id)
    {
        $job = Job::query()
            ->where('provider', 'whatjobs')
            ->where('provider_job_id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return view('whatjobs.jobs.show', [
            'job' => $job,
        ]);
    }

    public function location(string $location)
    {
        /*
        |--------------------------------------------------------------------------
        | Find the original location value
        |--------------------------------------------------------------------------
        |
        | Example:
        | Ahmedabad      -> ahmedabad
        | Hubli-Dharwad  -> hubli-dharwad
        |
        | We compare the slug but query the database using the
        | original location value.
        |
        */

        $locationName = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', true)
            ->whereNotNull('location')
            ->pluck('location')
            ->filter()
            ->first(function ($value) use ($location) {
                return Str::slug($value) === $location;
            });

        /*
        |--------------------------------------------------------------------------
        | Location not found
        |--------------------------------------------------------------------------
        */

        abort_unless($locationName, 404);


        /*
        |--------------------------------------------------------------------------
        | Get jobs for this location
        |--------------------------------------------------------------------------
        */

        $jobs = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', true)
            ->where('location', $locationName)
            ->latest()
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Location page
        |--------------------------------------------------------------------------
        */

        return view('whatjobs.jobs.location', [
            'jobs' => $jobs,
            'location' => $locationName,
        ]);
    }
}