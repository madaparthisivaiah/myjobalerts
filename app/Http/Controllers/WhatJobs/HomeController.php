<?php

namespace App\Http\Controllers\WhatJobs;

use App\Http\Controllers\Controller;
use App\Services\WhatJobs\HomePageJobService;

class HomeController extends Controller
{
    public function __construct(
        protected HomePageJobService $homePageJobService
    ) {
    }

    public function index()
    {
        $data = $this->homePageJobService->getHomePageData();
        return view('home', $data);
    }
}