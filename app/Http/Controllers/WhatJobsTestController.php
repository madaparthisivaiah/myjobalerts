<?php

namespace App\Http\Controllers;

use App\Services\WhatJobsService;
use Illuminate\Http\Request;

class WhatJobsTestController extends Controller
{
    public function index(
        Request $request,
        WhatJobsService $whatJobs
    ) {
        $page = max(
            (int) $request->query('page', 1),
            1
        );

        $jobs = $whatJobs->getIndiaJobs(
            userIp: $request->ip(),
            userAgent: $request->userAgent(),
            page: $page
        );

        return response()->json($jobs);
    }
}