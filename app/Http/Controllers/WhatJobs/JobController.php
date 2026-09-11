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
        /*
        |--------------------------------------------------------------------------
        | Default Request Values
        |--------------------------------------------------------------------------
        |
        | Always initialize these variables so they are never undefined.
        |
        */

        $keyword = '';
        $location = '';
        $company = '';

        /*
        |--------------------------------------------------------------------------
        | Handle Route Value
        |--------------------------------------------------------------------------
        */

        if ($value) {

            /*
            |--------------------------------------------------------------------------
            | Location Route
            |--------------------------------------------------------------------------
            */

            if ($request->routeIs('jobs.location')) {

                $locationMap = [
                    'bangalore-bazaar' => 'Bangalore',
                    // add more here
                ];

                $location = $locationMap[$value]
                    ?? str_replace('-', ' ', $value);

                $request->merge([
                    'location' => $location,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Company Route
            |--------------------------------------------------------------------------
            */

            if ($request->routeIs('jobs.company')) {

                $companyMap = [
                    'mufg-global-service-mgs' => 'MUFG Global Service',
                    'artech-llc'              => 'Artech L.L.C.',
                    // add more here
                ];

                $company = $companyMap[$value]
                    ?? str_replace('-', ' ', $value);

                $request->merge([
                    'company' => $company,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Get Request Values Safely
        |--------------------------------------------------------------------------
        */

        $keyword = trim((string) $request->input('keyword', ''));
        $location = trim((string) $request->input('location', ''));
        $company = trim((string) $request->input('company', ''));

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', true);

        /*
        |--------------------------------------------------------------------------
        | Keyword
        |--------------------------------------------------------------------------
        */

        if ($keyword !== '') {

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

        if ($location !== '') {

            $query->where(
                'location',
                'like',
                "%{$location}%"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Company
        |--------------------------------------------------------------------------
        */

        if ($company !== '') {

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

        $sort = $request->input('sort', 'latest');

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
        | Sidebar Companies
        |--------------------------------------------------------------------------
        */

        $companies = [];

        /*
        |--------------------------------------------------------------------------
        | Dynamic SEO
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | Default SEO
        |--------------------------------------------------------------------------
        */

        $pageTitle = 'Search Jobs in India | MyJobAlerts';

        $metaDescription =
            'Find the latest jobs in India by job title, company and location. Browse thousands of job opportunities on MyJobAlerts.';

        /*
        |--------------------------------------------------------------------------
        | Keyword + Location
        |--------------------------------------------------------------------------
        */

        if ($keyword !== '' && $location !== '') {

            $pageTitle =
                "{$keyword} Jobs in {$location}, India | MyJobAlerts";

            $metaDescription =
                "Find the latest {$keyword} jobs in {$location}, India. Browse current job opportunities from top companies and apply for jobs on MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Keyword Only
        |--------------------------------------------------------------------------
        */

        elseif ($keyword !== '') {

            $pageTitle =
                "{$keyword} Jobs in India | MyJobAlerts";

            $metaDescription =
                "Find the latest {$keyword} jobs in India. Browse current job opportunities from top companies and apply for jobs on MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Company + Location
        |--------------------------------------------------------------------------
        */

        elseif ($company !== '' && $location !== '') {

            $pageTitle =
                "{$company} Jobs in {$location}, India | MyJobAlerts";

            $metaDescription =
                "Find the latest {$company} jobs in {$location}, India. Browse current job openings and apply for available positions on MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Company Only
        |--------------------------------------------------------------------------
        */

        elseif ($company !== '') {

            $pageTitle =
                "{$company} Jobs in India | MyJobAlerts";

            $metaDescription =
                "Find the latest {$company} jobs in India. Browse current job openings and apply for available positions on MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Location Only
        |--------------------------------------------------------------------------
        */

        elseif ($location !== '') {

            $pageTitle =
                "Jobs in {$location}, India | MyJobAlerts";

            $metaDescription =
                "Find the latest jobs in {$location}, India. Browse current job opportunities from top companies and apply for jobs on MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Clean SEO Values
        |--------------------------------------------------------------------------
        */

        $pageTitle = preg_replace('/\s+/', ' ', trim($pageTitle));

        $metaDescription = preg_replace(
            '/\s+/',
            ' ',
            trim($metaDescription)
        );

        /*
        |--------------------------------------------------------------------------
        | Limit SEO Length
        |--------------------------------------------------------------------------
        */

        $pageTitle = \Illuminate\Support\Str::limit(
            $pageTitle,
            70,
            ''
        );

        $metaDescription = \Illuminate\Support\Str::limit(
            $metaDescription,
            160,
            ''
        );

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('whatjobs.jobs.index', [

            'jobs' => $jobs,

            'companies' => $companies,

            'keyword' => $keyword,

            'location' => $location,

            'company' => $company,

            'sort' => $sort,

            'pageTitle' => $pageTitle,

            'metaDescription' => $metaDescription,
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

    public function showjob(string $slug)
    {
        $job = Job::query()
            ->where('provider', 'whatjobs')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('whatjobs.jobs.show', [
            'job' => $job,
        ]);
        //return view('whatjobs.show', compact('job'));
    }
}