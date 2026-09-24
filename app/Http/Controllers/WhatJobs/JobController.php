<?php

namespace App\Http\Controllers\WhatJobs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class JobController extends Controller
{
    /**
     * Columns actually needed by the index/listing view.
     * Selecting only these avoids pulling large/unused text columns
     * (e.g. full job descriptions) off disk for every row on every
     * listing request.
     *
     * Adjust this list to match what whatjobs.jobs.index actually uses.
     */
    private const LIST_COLUMNS = [
        'id',
        'provider',
        'provider_job_id',
        'slug',
        'title',
        'snippet',
        'job_url',
        'company',
        'location',
        'is_active',
        'employment_type',
        'published_at',
    ];

    /**
     * Display WhatJobs listings.
     */
    public function index(Request $request, ?string $value = null)
    {
        $keyword = '';
        $location = '';
        $company = '';

        if ($value) {

            if ($request->routeIs('jobs.location')) {

                $locationMap = [
                    'bangalore-bazaar' => 'Bangalore',
                    // add more here
                ];

                $location = $locationMap[$value]
                    ?? str_replace('-', ' ', $value);

                $request->merge(['location' => $location]);
            }

            if ($request->routeIs('jobs.company')) {

                $companyMap = [
                    'mufg-global-service-mgs' => 'MUFG Global Service',
                    'artech-llc'              => 'Artech L.L.C.',
                    // add more here
                ];

                $company = $companyMap[$value]
                    ?? str_replace('-', ' ', $value);

                $request->merge(['company' => $company]);
            }
        }

        $keyword = Str::title(trim((string) $request->input('keyword', '')));
        $location = Str::title(trim((string) $request->input('location', '')));
        $company = Str::title(trim((string) $request->input('company', '')));

        $sort = $request->input('sort', 'latest');
        $page = (int) $request->input('page', 1);

        $hasFilters = $keyword !== '' || $location !== '' || $company !== '';

        /*
        |--------------------------------------------------------------------------
        | Cache the unfiltered "browse all" view
        |--------------------------------------------------------------------------
        |
        | Page 1 of the default listing (no keyword/location/company, default
        | sort) is almost certainly the most-hit URL on the site and is
        | identical for every visitor at a given moment. Cache it briefly so
        | repeat/concurrent hits skip the DB entirely. Filtered/search
        | requests are far more varied and not worth caching the same way.
        */
        $cacheKey = "whatjobs.index.page.{$page}.sort.{$sort}";

        if (!$hasFilters) {
            $jobs = Cache::remember($cacheKey, 60, function () use ($sort) {
                return $this->buildQuery('', '', '', $sort)
                    ->paginate(20);
            });
        } else {
            $jobs = $this->buildQuery($keyword, $location, $company, $sort)
                ->paginate(20)
                ->withQueryString();
        }

        $httpStatus = $jobs->isEmpty() ? 404 : 200;

        $companies = [];

        [$pageTitle, $metaDescription] = $this->buildSeo($keyword, $location, $company);
       
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
     * Build the base listing query with column selection + filters applied.
     * Pulled out of index() so the cached and non-cached paths share one
     * definition instead of drifting apart.
     */
    private function buildQuery(string $keyword, string $location, string $company, string $sort)
    {
        $query = Job::query()
            ->select(self::LIST_COLUMNS)
            ->where('provider', 'whatjobs')
            ->where('is_active', true);

        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('company', 'like', "%{$keyword}%");
            });
        }

        if ($location !== '') {
            $query->where('location', 'like', "%{$location}%");
        }

        if ($company !== '') {
            $normalizedCompany = preg_replace('/[^a-z0-9]/', '', strtolower($company));

            // If you've added the company_normalized column from the earlier
            // migration, use that instead (it's indexed):
            // $query->where('company_normalized', 'like', "%{$normalizedCompany}%");

            $query->whereRaw(
                "LOWER(REPLACE(REPLACE(REPLACE(company, '.', ''), ' ', ''), '-', '')) LIKE ?",
                ["%{$normalizedCompany}%"]
            );
        }

        return $sort === 'oldest'
            ? $query->orderBy('published_at', 'asc')
            : $query->orderByDesc('published_at');
    }

    private function buildSeo(string $keyword, string $location, string $company): array
    {
        $pageTitle = 'Search Jobs in India - Latest Vacancies and Careers | MyJobAlerts';
        $metaDescription = 'Find the latest jobs in India by job title, company and location. Browse current job vacancies, explore career opportunities and apply directly through trusted job listings on MyJobAlerts.';

        if ($keyword !== '' && $location !== '') {
            $pageTitle = "{$keyword} Jobs in {$location}, India - Latest Job Vacancies and Careers | MyJobAlerts";
            $metaDescription = "Find the latest {$keyword} jobs in {$location}, India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";
        } elseif ($keyword !== '') {
            $pageTitle = "{$keyword} Jobs in India - Latest Job Vacancies | MyJobAlerts";
            $metaDescription = "Find the latest {$keyword} jobs in India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";
        } elseif ($company !== '' && $location !== '') {
            $pageTitle = "{$company} Jobs in {$location}, India - Latest Job Vacancies | MyJobAlerts";
            $metaDescription = "Find the latest {$company} jobs in {$location}, India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";
        } elseif ($company !== '') {
            $pageTitle = "{$company} Jobs in India - Latest Job Vacancies and Careers | MyJobAlerts";
            $metaDescription = "Find the latest {$company} jobs in India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";
        } elseif ($location !== '') {
            $pageTitle = "Jobs in {$location}, India - Latest Job Vacancies and Careers | MyJobAlerts";
            $metaDescription = "Find the latest jobs in {$location}, India. Browse current job vacancies from leading companies and explore career opportunities on MyJobAlerts.";
        }

        $pageTitle = Str::limit(preg_replace('/\s+/', ' ', trim($pageTitle)), 70, '');
        $metaDescription = Str::limit(preg_replace('/\s+/', ' ', trim($metaDescription)), 160, '');

        return [$pageTitle, $metaDescription];
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

        if (!$job) {
            abort(404);
        }

        $isExpired = ((int) $job->is_active === 0);

        if ($isExpired) {
            return response()->view('whatjobs.jobs.show', [
                'job' => $job,
                'isExpired' => true,
            ], 410);
        }

        return view('whatjobs.jobs.show', [
            'job' => $job,
            'isExpired' => false,
        ]);
    }

    /**
     * Display jobs for a given location slug.
     *
     * Uses location_slug (see the column-adding migration) for a single
     * indexed lookup instead of pulling every location value into PHP
     * and slugging them one by one.
     */
    public function location(string $location)
    {
        $job = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', true)
            ->where('location_slug', $location)
            ->first();

        abort_unless($job, 404);

        $locationName = $job->location;

        $jobs = Job::query()
            ->select(self::LIST_COLUMNS)
            ->where('provider', 'whatjobs')
            ->where('is_active', true)
            ->where('location', $locationName)
            ->latest('published_at')
            ->paginate(20)
            ->withQueryString();

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

        if (!$job) {
            abort(404);
        }

        $isExpired = ((int) $job->is_active === 0);

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

        return view('whatjobs.jobs.show_new', [
            'job' => $job,
            'isExpired' => false,
        ]);
    }
}