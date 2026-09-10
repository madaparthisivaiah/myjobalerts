<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
//use App\Http\Controllers\JobController;
//use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController; 
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WhatJobsTestController;
use App\Services\WhatJobsService;

use App\Http\Controllers\WhatJobs\JobController as WhatJobsJobController;
use App\Http\Controllers\WhatJobs\HomeController;
use App\Http\Controllers\WhatJobs\JobController;

//Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');

//Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
//Route::get('/jobs/{state}', [JobController::class, 'jobsbystate'])->name('jobs.by-state');
//Route::get('/company/{company}', [JobController::class, 'jobsbycompany'])->name('jobs.by-company');
Route::get('/about-us', function () {
    return view('aboutus');
});
Route::post('/contact-us', [ContactController::class, 'submit'])->name('contact.submit');

Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');

Route::view('/terms-and-conditions', 'terms-and-conditions')->name('terms-and-conditions');

Route::view('/cookie-policy', 'cookie-policy')->name('cookie-policy');

Route::view('/disclaimer', 'disclaimer')->name('disclaimer');

Route::view('/contact', 'contact')->name('contact');

Route::view('/faqs', 'faqs')->name('faq');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');


Route::get('/test-whatjobs', function (
    WhatJobsService $whatJobs
) {

    $result = $whatJobs->search([
        'keyword' => '',
        'location' => 'India',
        'limit' => 10,
        'page' => 1,
    ]);

    return response()->json($result);

});

Route::get(
    '/test/whatjobs',
    [WhatJobsTestController::class, 'index']
);

Route::get('/job/{job}', [JobController::class, 'show'])
    ->name('jobs.show');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');

Route::get('/jobs/{location}', [JobController::class, 'index'])->name('jobs.location');
Route::get('/company/{company}', [JobController::class, 'index'])->name('jobs.company');

Route::get('/viewjob/{slug}', [JobController::class, 'showjob']); 