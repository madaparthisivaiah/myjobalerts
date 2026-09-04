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
        ]);
    }

    public function jobsbystate($state, Request $request)
    {
        
        $location = str_replace('-', ' ', trim($state));     

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
        ]);
    }


    public function jobsbycompany($company, Request $request)
    {
        $keyword = str_replace('-', ' ', trim($company));
        $keyword = preg_replace('/\s+jobs\s*$/i', '', $keyword);
        $keyword = trim($keyword);

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
        ]);
    }
}