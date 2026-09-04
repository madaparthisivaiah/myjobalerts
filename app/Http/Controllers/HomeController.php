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
        $alldata = $this->homePageJobService->alllocations();
        $cities = $alldata[0];
        $states = $alldata[1];

        //dd($alldata);

        $companies = $this->homePageJobService->getTopCompanies();    

        return view('home', [   
            'companies' => $companies,
            'cities' => $cities,
            'states' => $states,
        ]);
    }
}