<?php

namespace App\Http\Controllers;

use App\Services\IndiaJobsSearchService;
use Illuminate\Http\Request;

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
        $keyword = trim(
            (string) $request->input('keyword', '')
        );

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

            'keyword' => $keyword,
            'location' => $location,
            'sort' => $sort,

            'cachedPages' => $result['cachedPages'],
            'cachedJobs' => $result['cachedJobs'],
        ]);
    }
}