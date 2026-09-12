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

        $keyword = Str::title(trim((string) $request->input('keyword', '')));
        $location = Str::title(trim((string) $request->input('location', '')));
        $company = Str::title(trim((string) $request->input('company', '')));

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

        /*****No jobs with pagainations iw ll show 404**** */
        $httpStatus = $jobs->isEmpty() ? 404 : 200;
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

        $pageTitle = 'Search Jobs in India - Latest Vacancies and Careers | MyJobAlerts';

        $metaDescription = 'Find the latest jobs in India by job title, company and location. Browse current job vacancies, explore career opportunities and apply directly through trusted job listings on MyJobAlerts.';

        /*
        |--------------------------------------------------------------------------
        | Keyword + Location
        |--------------------------------------------------------------------------
        */

        if ($keyword !== '' && $location !== '') {

            $pageTitle = "{$keyword} Jobs in {$location}, India - Latest Job Vacancies and Careers | MyJobAlerts";

            $metaDescription = "Find the latest {$keyword} jobs in {$location}, India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Keyword Only
        |--------------------------------------------------------------------------
        */

        elseif ($keyword !== '') {
            $pageTitle = "{$keyword} Jobs in India - Latest Job Vacancies | MyJobAlerts";
            $metaDescription = "Find the latest {$keyword} jobs in India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Company + Location
        |--------------------------------------------------------------------------
        */

        elseif ($company !== '' && $location !== '') {
            $pageTitle = "{$company} Jobs in {$location}, India - Latest Job Vacancies | MyJobAlerts";
            $metaDescription = "Find the latest {$company} jobs in {$location}, India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Company Only
        |--------------------------------------------------------------------------
        */

        elseif ($company !== '') {
            $pageTitle = "{$company} Jobs in India - Latest Job Vacancies and Careers | MyJobAlerts";
            $metaDescription = "Find the latest {$company} jobs in India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Location Only
        |--------------------------------------------------------------------------
        */

        elseif ($location !== '') {
            $pageTitle = "Jobs in {$location}, India - Latest Job Vacancies and Careers | MyJobAlerts";
            $metaDescription = "Find the latest jobs in {$location}, India. Browse current job vacancies from leading companies and explore career opportunities on MyJobAlerts.";
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

        return response()->view('whatjobs.jobs.index', [
            'jobs' => $jobs,
            'companies' => $companies,
            'keyword' => $keyword,
            'location' => $location,
            'company' => $company,
            'sort' => $sort,
            'pageTitle' => $pageTitle,
            'metaDescription' => $metaDescription,
        ], $httpStatus);
    }

    /**
     * Display a single WhatJobs job.
     */
    public function show(string $id)
    {
        $job = Job::query()
            ->where('provider', 'whatjobs')
            ->where('provider_job_id', $id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Job Not Found
        |--------------------------------------------------------------------------
        */

        if (!$job) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Expired / Inactive Job
        |--------------------------------------------------------------------------
        |
        | WhatJobs:
        | is_active = true → Active
        | is_active = false → Inactive / Expired
        |
        */

        $isExpired = ((int) $job->is_active === 0);

        if ($isExpired) {

            return response()
                ->view('whatjobs.jobs.show', [
                    'job' => $job,
                    'isExpired' => true,
                ], 410);
        }

        /*
        |--------------------------------------------------------------------------
        | Active Job
        |--------------------------------------------------------------------------
        */

        return view('whatjobs.jobs.show', [
            'job' => $job,
            'isExpired' => false,
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
            ->first();
            //dd($job);

        /*
        |--------------------------------------------------------------------------
        | Job Not Found
        |--------------------------------------------------------------------------
        */

        if (!$job) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Check Job Status
        |--------------------------------------------------------------------------
        |
        | WhatJobs:
        | is_active = 1 → Active
        | is_active = 0 → Inactive / Expired
        |
        */

        $isExpired = ((int) $job->is_active === 0);
        /*
        |--------------------------------------------------------------------------
        | Expired Job
        |--------------------------------------------------------------------------
        |
        | Return HTTP 410 Gone.
        | Do not render expired job content.
        |
        */
        if ($isExpired) {
            return response()->view(
                'whatjobs.jobs.show_new',
                [
                    'job' => $job,
                    'isExpired' => true,
                ],
                410
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Active Job
        |--------------------------------------------------------------------------
        */
        return view('whatjobs.jobs.show_new', [
            'job' => $job,
            'isExpired' => false,
        ]);
    }

}