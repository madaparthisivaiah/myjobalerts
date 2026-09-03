<?php

namespace App\Http\Controllers;

class LocationController extends Controller
{
    public function index()
    {
        $states = config('india_locations', []);

        return view('states', compact('states'));
    }
}
