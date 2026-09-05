<?php

namespace App\Http\Controllers;

use App\Services\IndiaJobsSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobController extends Controller
{
    protected IndiaJobsSearchService $indiaJobsSearch;

    public function __construct(
        IndiaJobsSearchService $indiaJobsSearch
    ) {
        $this->indiaJobsSearch = $indiaJobsSearch;
    }

    public function index(Request $request)
    {
        $keywords = trim(
            (string) $request->input('keyword', '')
        );
      

        // Remove common filler/stopwords often appended to job searches
        $stopWords = ['jobs', 'job', 'near', 'me', 'vacancy', 'vacancies', 'openings', 'opening', 'hiring'];

        $words = preg_split('/\s+/', strtolower($keywords), -1, PREG_SPLIT_NO_EMPTY);

        $words = array_filter($words, function ($word) use ($stopWords) {
            return !in_array($word, $stopWords, true);
        }); 

        $keyword = trim(implode(' ', $words));

        $location = trim(
            (string) $request->input('location', '')
        );

        /****Meta Tags****/
        if ($keyword && $location) {

            $metatitle = "{$keyword} Jobs in {$location} | Latest Vacancies - MyJobAlerts";

            $metaDescription = "Find the latest {$keyword} jobs in {$location}. Explore current vacancies and discover opportunities from companies hiring in {$location}.";

        } elseif ($keyword) {

            $metatitle = "{$keyword} Jobs in India | Latest Job Vacancies - MyJobAlerts";

            $metaDescription = "Find the latest {$keyword} jobs in India. Explore current job vacancies and discover opportunities from companies hiring now.";

        } elseif ($location) {

            $metatitle = "Jobs in {$location} | Latest Job Vacancies - MyJobAlerts";

            $metaDescription = "Find the latest jobs in {$location}. Explore current job vacancies across companies, industries and job categories on MyJobAlerts.";

        } else {

            $metatitle = "Latest Jobs in India | Job Vacancies & Careers - MyJobAlerts";

            $metaDescription = "Find the latest job opportunities in India. Search jobs by keyword, location, company and category on MyJobAlerts.";
        }

        $page = max(
            (int) $request->input('page', 1),
            1
        );

        $perPage = 20;

        $sort = $request->input(
            'sort',
            'relevance'
        );

        if (!in_array($sort, ['relevance', 'date'], true)) {
            $sort = 'relevance';
        }

        $result = $this->indiaJobsSearch->search(
            keyword: $keyword,
            location: $location,
            page: $page,
            perPage: $perPage,
            sort: $sort
        );

        return view('jobs.index', [
            'jobs' => $result['jobs'],

            // Keep "hits" for the existing Blade.
            'hits' => $result['total'],

            'total' => $result['total'],
            'pages' => $result['lastPage'],

            'currentPage' => $result['currentPage'],

            'from' => $result['from'],
            'to' => $result['to'],

            'keyword' => $keywords,
            'location' => $location,
            'sort' => $sort,

            'cachedPages' => $result['cachedPages'],
            'cachedJobs' => $result['cachedJobs'],

            'metatitle' => $metatitle,
            'metaDescription' => $metaDescription,
        ]);
    }

    public function jobsbystate($state, Request $request)
    {
        $location = trim($state);
        $locations = [
            'hubli-dharwad' => 'Hubli-Dharwad',
        ];
        
        $location = $locations[$location] ?? str_replace('-', ' ', $location);
        
        /****Meta Tags****/
        if ($location) {

            $metatitle = "Jobs in {$location} | Latest Job Vacancies - MyJobAlerts";

            $metaDescription = "Find the latest jobs in {$location}. Explore current job vacancies across companies, industries and job categories on MyJobAlerts.";

        } else {

            $metatitle = "Latest Jobs in India | Job Vacancies & Careers - MyJobAlerts";

            $metaDescription = "Find the latest job opportunities in India. Search jobs by keyword, location, company and category on MyJobAlerts.";
        }


        $page = max(
            (int) $request->input('page', 1),
            1
        );

        $perPage = 20;

        $sort = $request->input(
            'sort',
            'relevance'
        );

        if (!in_array($sort, ['relevance', 'date'], true)) {
            $sort = 'relevance';
        }

        $result = $this->indiaJobsSearch->search(
            location: $location,
            page: $page,
            perPage: $perPage,
            sort: $sort
        );

        return view('jobs.index', [
            'jobs' => $result['jobs'],

            // Keep "hits" for the existing Blade.
            'hits' => $result['total'],

            'total' => $result['total'],
            'pages' => $result['lastPage'],

            'currentPage' => $result['currentPage'],

            'from' => $result['from'],
            'to' => $result['to'],

            'location' => $location,
            'sort' => $sort,

            'cachedPages' => $result['cachedPages'],
            'cachedJobs' => $result['cachedJobs'],
            'metatitle' => $metatitle,
            'metaDescription' => $metaDescription,
        ]);
    }


    public function jobsbycompany($company, Request $request)
    {
        $keyword = str_replace('-', ' ', trim($company));
        $keyword = preg_replace('/\s+jobs\s*$/i', '', $keyword);
        $keyword = trim($keyword);

        /*
        * Convert URL slug/company slug back to the actual company name
        * used by CareerJet.
        */
        $companyMap = [
            'lowes' => "Lowe's",
        ];

        $keyword = $companyMap[strtolower($keyword)] ?? $keyword;

        /****Meta Tags****/
        if ($keyword) {

            $metatitle = "{$keyword} Jobs in India | Latest Job Vacancies - MyJobAlerts";

            $metaDescription = "Find the latest {$keyword} jobs in India. Explore current job vacancies and discover opportunities from companies hiring now.";

        }  else {

            $metatitle = "Latest Jobs in India | Job Vacancies & Careers - MyJobAlerts";

            $metaDescription = "Find the latest job opportunities in India. Search jobs by keyword, location, company and category on MyJobAlerts.";
        }

        $page = max(
            (int) $request->input('page', 1),
            1
        );

        $perPage = 20;

        $sort = $request->input(
            'sort',
            'relevance'
        );

        if (!in_array($sort, ['relevance', 'date'], true)) {
            $sort = 'relevance';
        }

        $result = $this->indiaJobsSearch->search(
            keyword: $keyword,
            page: $page,
            perPage: $perPage,
            sort: $sort
        );

        return view('jobs.index', [
            'jobs' => $result['jobs'],

            // Keep "hits" for the existing Blade.
            'hits' => $result['total'],

            'total' => $result['total'],
            'pages' => $result['lastPage'],

            'currentPage' => $result['currentPage'],

            'from' => $result['from'],
            'to' => $result['to'],

            'company' => $company,
            'sort' => $sort,

            'cachedPages' => $result['cachedPages'],
            'cachedJobs' => $result['cachedJobs'],
            'metatitle' => $metatitle,
            'metaDescription' => $metaDescription,
        ]);
    }
}