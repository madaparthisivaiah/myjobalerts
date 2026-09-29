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

    private const RELATED_JOB_COLUMNS = [
        'id',
        'slug',
        'title',
        'company',
        'location',
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
                    'gyansys-inc'             => 'GyanSys Inc.',
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
        // Remove common filler/stopwords often appended to job searches
        $stopWords = ['jobs', 'job', 'near', 'me', 'vacancy', 'vacancies', 'openings', 'opening', 'hiring'];

        $words = preg_split('/\s+/', strtolower($keyword), -1, PREG_SPLIT_NO_EMPTY);

        $words = array_filter($words, function ($word) use ($stopWords) {
            return !in_array($word, $stopWords, true);
        }); 

        $keyword = trim(implode(' ', $words));

        // if ($keyword !== '') {

        //     $search = "%{$keyword}%";

        //     $query->where(function ($q) use ($search) {

        //         $q->where(
        //             'title',
        //             'like',
        //             $search
        //         )->orWhere(
        //             'company',
        //             'like',
        //             $search
        //         );
        //     });
        // }

        if ($keyword !== '') {
            $query->whereFullText(
                ['title', 'company'],
                $keyword
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Location search
        |--------------------------------------------------------------------------
        */

        // if ($location !== '') {

        //     $query->where(
        //         'location',
        //         'like',
        //         "%{$location}%"
        //     );
        // }

        if ($location !== '') {
            $location = trim($location);

            $parts = preg_split('/[\s,]+/', strtolower($location), -1, PREG_SPLIT_NO_EMPTY);

            $parts = array_values(array_unique($parts));

            $query->where(function ($q) use ($location, $parts) {
                // Full location exact match
                $q->whereRaw('LOWER(location) = ?', [strtolower($location)]);

                // Individual words exact match
                foreach ($parts as $part) {
                    $q->orWhereRaw('LOWER(location) = ?', [$part]);
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Company search
        |--------------------------------------------------------------------------
        |
        | Normalize the search value in PHP, then normalize the database
        | company value during comparison.
        |
        */

        /*if ($company !== '') {

            $normalizedCompany = preg_replace(
                '/[^a-z0-9]/',
                '',
                strtolower($company)
            );

            if ($normalizedCompany !== '') {

                $query->whereRaw(
                    "LOWER(
                        REPLACE(
                            REPLACE(
                                REPLACE(company, '.', ''),
                                ' ',
                                ''
                            ),
                            '-',
                            ''
                        )
                    ) LIKE ?",
                    ["%{$normalizedCompany}%"]
                );
            }
        }*/
            if ($company !== '') {
                $query->where('company', $company);
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

        /*
|--------------------------------------------------------------------------
| Related jobs
|--------------------------------------------------------------------------
|
| Priority 1:
| Same location + related title/company
|
| Priority 2:
| Related title/company anywhere in India
|
| Optimization:
| - Uses existing FULLTEXT index:
|   jobs_title_company_fulltext
| - Maximum 2 database queries
| - Maximum 8 results
| - Only required columns selected
| - No LIKE "%keyword%" scans
| - No SQL CASE/relevance calculation
|
*/

$relatedJobs = collect();

$titleWords = preg_split(
    '/[^a-zA-Z0-9]+/',
    strtolower((string) $job->title),
    -1,
    PREG_SPLIT_NO_EMPTY
);

/*
|--------------------------------------------------------------------------
| Remove common / generic title words
|--------------------------------------------------------------------------
*/

$stopWords = [
    'a',
    'an',
    'and',
    'at',
    'for',
    'from',
    'in',
    'is',
    'of',
    'on',
    'or',
    'the',
    'to',
    'with',
    'job',
    'jobs',

    'senior',
    'junior',
    'manager',
    'management',
    'executive',
    'lead',
    'leader',
    'head',
    'director',
    'assistant',
    'associate',
    'officer',
    'specialist',
    'professional',
    'trainee',
    'intern',
    'internship',
];

$titleWords = array_values(
    array_filter(
        array_unique($titleWords),
        function ($word) use ($stopWords) {
            return !in_array($word, $stopWords, true)
                && strlen($word) >= 3;
        }
    )
);

/*
|--------------------------------------------------------------------------
| Keep maximum 5 useful keywords
|--------------------------------------------------------------------------
*/

$titleWords = array_slice($titleWords, 0, 5);

if (!empty($titleWords)) {

    /*
    |--------------------------------------------------------------------------
    | Build FULLTEXT search string
    |--------------------------------------------------------------------------
    |
    | Example:
    |
    | Senior Customer Success Manager
    |
    | becomes:
    |
    | +customer +success
    |
    | This prevents a result matching only "customer" or only "success".
    |
    */

    $searchTerms = collect($titleWords)
        ->map(fn ($word) => '+' . $word . '*')
        ->implode(' ');

    /*
    |--------------------------------------------------------------------------
    | 1. Same location + related title
    |--------------------------------------------------------------------------
    */

    if (!empty($job->location)) {

        $relatedJobs = Job::query()
            ->select(self::RELATED_JOB_COLUMNS)
            ->where('provider', 'whatjobs')
            ->where('is_active', 1)
            ->where('id', '!=', $job->id)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->where('location', $job->location)
            ->whereFullText(
                ['title', 'company'],
                $searchTerms
            )
            ->orderByDesc('published_at')
            ->limit(6)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Related title anywhere in India
    |--------------------------------------------------------------------------
    */

    if ($relatedJobs->count() < 8) {

        $remaining = 8 - $relatedJobs->count();

        $excludeIds = $relatedJobs
            ->pluck('id')
            ->push($job->id)
            ->all();

        $indiaRelatedJobs = Job::query()
            ->select(self::RELATED_JOB_COLUMNS)
            ->where('provider', 'whatjobs')
            ->where('is_active', 1)
            ->whereNotIn('id', $excludeIds)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->whereFullText(
                ['title', 'company'],
                $searchTerms
            )
            ->orderByDesc('published_at')
            ->limit($remaining)
            ->get();

        $relatedJobs = $relatedJobs->concat(
            $indiaRelatedJobs
        );
    }
}

        return view(
            'whatjobs.jobs.show_new',
            [
                'job' => $job,
                'relatedJobs' => $relatedJobs,
                'isExpired' => false,
            ]
        );
    }
}