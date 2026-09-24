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
     *
     * Selecting only these avoids pulling large/unused text columns
     * (e.g. full job descriptions) from the database for listing requests.
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

        /*
        |--------------------------------------------------------------------------
        | Route-based filters
        |--------------------------------------------------------------------------
        */

        if ($value) {

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
        | Normalize search input
        |--------------------------------------------------------------------------
        |
        | Keep the actual search text intact. MySQL normally handles
        | case-insensitive comparison through the column collation.
        |
        */

        $keyword = trim((string) $request->input('keyword', ''));
        $location = trim((string) $request->input('location', ''));
        $company = trim((string) $request->input('company', ''));

        /*
        |--------------------------------------------------------------------------
        | Sort + pagination
        |--------------------------------------------------------------------------
        */

        $sort = $request->input('sort', 'latest');

        $page = max(
            1,
            (int) $request->input('page', 1)
        );

        /*
        |--------------------------------------------------------------------------
        | Detect filters
        |--------------------------------------------------------------------------
        */

        $hasFilters =
            $keyword !== '' ||
            $location !== '' ||
            $company !== '';

        /*
        |--------------------------------------------------------------------------
        | Cache only the unfiltered browse listing
        |--------------------------------------------------------------------------
        |
        | Search combinations can be extremely numerous, so only the
        | default browse listing is cached.
        |
        | 7200 seconds = 2 hours.
        |
        */

        if (!$hasFilters) {

            $cacheKey = "whatjobs.index.page.{$page}.sort.{$sort}";

            $jobs = Cache::remember(
                $cacheKey,
                7200,
                function () use ($sort) {

                    return $this->buildQuery(
                        '',
                        '',
                        '',
                        $sort
                    )->paginate(20);
                }
            );

        } else {

            /*
            |--------------------------------------------------------------------------
            | Search query
            |--------------------------------------------------------------------------
            |
            | Do not cache arbitrary searches. There can be a very large
            | number of keyword/location/company combinations.
            |
            */

            $jobs = $this->buildQuery(
                $keyword,
                $location,
                $company,
                $sort
            )
                ->paginate(20)
                ->withQueryString();
        }

        /*
        |--------------------------------------------------------------------------
        | HTTP status
        |--------------------------------------------------------------------------
        */

        $httpStatus = $jobs->isEmpty()
            ? 404
            : 200;

        /*
        |--------------------------------------------------------------------------
        | Existing variables
        |--------------------------------------------------------------------------
        */

        $companies = [];

        /*
        |--------------------------------------------------------------------------
        | SEO
        |--------------------------------------------------------------------------
        */

        [$pageTitle, $metaDescription] = $this->buildSeo(
            $keyword,
            $location,
            $company
        );

        /*
        |--------------------------------------------------------------------------
        | Return view
        |--------------------------------------------------------------------------
        */

        return response()->view(
            'whatjobs.jobs.index',
            [
                'jobs' => $jobs,
                'companies' => $companies,
                'keyword' => $keyword,
                'location' => $location,
                'company' => $company,
                'sort' => $sort,
                'pageTitle' => $pageTitle,
                'metaDescription' => $metaDescription,
            ],
            $httpStatus
        );
    }

    /**
     * Build the base listing/search query.
     *
     * Only active WhatJobs jobs are returned.
     */
    private function buildQuery(
        string $keyword,
        string $location,
        string $company,
        string $sort
    ) {
        /*
        |--------------------------------------------------------------------------
        | Base query
        |--------------------------------------------------------------------------
        */

        $query = Job::query()
            ->select(self::LIST_COLUMNS)
            ->where('provider', 'whatjobs')
            ->where('is_active', 1);

        /*
        |--------------------------------------------------------------------------
        | Keyword search
        |--------------------------------------------------------------------------
        |
        | Search in title and company.
        |
        */

        if ($keyword !== '') {

            $search = "%{$keyword}%";

            $query->where(function ($q) use ($search) {

                $q->where(
                    'title',
                    'like',
                    $search
                )->orWhere(
                    'company',
                    'like',
                    $search
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Location search
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
        | Company search
        |--------------------------------------------------------------------------
        |
        | company_normalized should contain a normalized version such as:
        |
        | "MUFG Global Service" -> "mufgglobalservice"
        | "Artech L.L.C."       -> "artechllc"
        |
        | This avoids running LOWER()/REPLACE() against the company column
        | for every database row.
        |
        */

        if ($company !== '') {

            $normalizedCompany = preg_replace(
                '/[^a-z0-9]/',
                '',
                strtolower($company)
            );

            if ($normalizedCompany !== '') {

                $query->where(
                    'company_normalized',
                    'like',
                    "%{$normalizedCompany}%"
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        if ($sort === 'oldest') {

            $query->orderBy(
                'published_at',
                'asc'
            );

        } else {

            $query->orderByDesc(
                'published_at'
            );
        }

        return $query;
    }

    /**
     * Build SEO title and meta description.
     */
    private function buildSeo(
        string $keyword,
        string $location,
        string $company
    ): array {
        $pageTitle =
            'Search Jobs in India - Latest Vacancies and Careers | MyJobAlerts';

        $metaDescription =
            'Find the latest jobs in India by job title, company and location. Browse current job vacancies, explore career opportunities and apply directly through trusted job listings on MyJobAlerts.';

        if ($keyword !== '' && $location !== '') {

            $pageTitle =
                "{$keyword} Jobs in {$location}, India - Latest Job Vacancies and Careers | MyJobAlerts";

            $metaDescription =
                "Find the latest {$keyword} jobs in {$location}, India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";

        } elseif ($keyword !== '') {

            $pageTitle =
                "{$keyword} Jobs in India - Latest Job Vacancies | MyJobAlerts";

            $metaDescription =
                "Find the latest {$keyword} jobs in India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";

        } elseif ($company !== '' && $location !== '') {

            $pageTitle =
                "{$company} Jobs in {$location}, India - Latest Job Vacancies | MyJobAlerts";

            $metaDescription =
                "Find the latest {$company} jobs in {$location}, India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";

        } elseif ($company !== '') {

            $pageTitle =
                "{$company} Jobs in India - Latest Job Vacancies and Careers | MyJobAlerts";

            $metaDescription =
                "Find the latest {$company} jobs in India. Browse current job vacancies, explore career opportunities and apply directly through MyJobAlerts.";

        } elseif ($location !== '') {

            $pageTitle =
                "Jobs in {$location}, India - Latest Job Vacancies and Careers | MyJobAlerts";

            $metaDescription =
                "Find the latest jobs in {$location}, India. Browse current job vacancies from leading companies and explore career opportunities on MyJobAlerts.";
        }

        /*
        |--------------------------------------------------------------------------
        | Limit SEO lengths
        |--------------------------------------------------------------------------
        */

        $pageTitle = Str::limit(
            preg_replace('/\s+/', ' ', trim($pageTitle)),
            70,
            ''
        );

        $metaDescription = Str::limit(
            preg_replace('/\s+/', ' ', trim($metaDescription)),
            160,
            ''
        );

        return [
            $pageTitle,
            $metaDescription,
        ];
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

        /*
        |--------------------------------------------------------------------------
        | Expired job
        |--------------------------------------------------------------------------
        |
        | is_active = 1 => ACTIVE
        | is_active = 0 => INACTIVE / expired
        |
        */

        $isExpired = ((int) $job->is_active === 0);

        if ($isExpired) {

            return response()->view(
                'whatjobs.jobs.show',
                [
                    'job' => $job,
                    'isExpired' => true,
                ],
                410
            );
        }

        return view(
            'whatjobs.jobs.show',
            [
                'job' => $job,
                'isExpired' => false,
            ]
        );
    }

    /**
     * Display jobs for a given location slug.
     *
     * Uses location_slug for a single indexed lookup.
     */
    public function location(string $location)
    {
        /*
        |--------------------------------------------------------------------------
        | Find location
        |--------------------------------------------------------------------------
        */

        $job = Job::query()
            ->where('provider', 'whatjobs')
            ->where('is_active', 1)
            ->where('location_slug', $location)
            ->first();

        abort_unless($job, 404);

        $locationName = $job->location;

        /*
        |--------------------------------------------------------------------------
        | Fetch active jobs for location
        |--------------------------------------------------------------------------
        */

        $jobs = Job::query()
            ->select(self::LIST_COLUMNS)
            ->where('provider', 'whatjobs')
            ->where('is_active', 1)
            ->where('location', $locationName)
            ->latest('published_at')
            ->paginate(20)
            ->withQueryString();

        return view(
            'whatjobs.jobs.location',
            [
                'jobs' => $jobs,
                'location' => $locationName,
            ]
        );
    }

    /**
     * Display a single job by slug.
     */
    public function showjob(string $slug)
    {
        $job = Job::query()
            ->where('provider', 'whatjobs')
            ->where('slug', $slug)
            ->first();

        if (!$job) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Expired job
        |--------------------------------------------------------------------------
        */

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

        return view(
            'whatjobs.jobs.show_new',
            [
                'job' => $job,
                'isExpired' => false,
            ]
        );
    }
}