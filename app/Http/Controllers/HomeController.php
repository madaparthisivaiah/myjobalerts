<?php

namespace App\Http\Controllers;

use App\Services\HomePageJobService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected HomePageJobService $homePageJobService;

    public function __construct(
        HomePageJobService $homePageJobService
    ) {
        $this->homePageJobService = $homePageJobService;
    }

    public function index(Request $request)
    {
        $city = (string) $request->input('city', '');

        $data = $this->homePageJobService->getHomePageData(
            city: $city,
            jobLimit: 6,
            companyLimit: 4
        );

        return view('home', [
            'jobs' => $data['jobs'],
            'companies' => $data['companies'],
            'city' => $data['city'],
        ]);
    }
}