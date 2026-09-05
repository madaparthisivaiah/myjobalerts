<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\JobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController; 
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{state}', [JobController::class, 'jobsbystate'])->name('jobs.by-state');
Route::get('/company/{company}', [JobController::class, 'jobsbycompany'])->name('jobs.by-company');
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

// Route::get('/', function () {
//     return view('home');
// });

// Route::get('/site', function () {
//     return view('home');
// });


Route::get('/test-careerjet', function () {

    $response = Http::withBasicAuth(
        config('services.careerjet.api_key'),
        ''
    )
    ->withHeaders([
        'Referer'    => 'http://127.0.0.1:8000/',
        'User-Agent' => request()->userAgent() ?: 'MyJobAlerts/1.0',
        'Accept'     => 'application/json',
    ])
    ->get(config('services.careerjet.base_url'), [
        'locale_code'   => 'en_IN',
        'keywords'      => 'software developer',
        'location'      => 'Hyderabad',
        'page'          => 1,
        'page_size'     => 1,
        'sort'          => 'date',
        'fragment_size' => 5000,
        'user_ip'       => request()->ip(),
        'user_agent'    => request()->userAgent() ?: 'MyJobAlerts/1.0',
    ]);

    return response()->json([
        'http_status' => $response->status(),
        'success'     => $response->successful(),
        'body'        => $response->json(),
        'raw_body'    => $response->body(),
    ]);
});

Route::get('/test-careerjet-key', function () {
    return response()->json([
        'key_exists' => !empty(config('services.careerjet.api_key')),
        'key_length' => strlen(config('services.careerjet.api_key') ?? ''),
        'base_url'   => config('services.careerjet.base_url'),
    ]);
});

// Route::get('/company/{company}', function ($company) {

//     $response = Http::withBasicAuth(
//         config('services.careerjet.api_key'),
//         ''
//     )->withHeaders([
//         'Referer' => config('services.careerjet.referer', 'http://127.0.0.1:8000/'),
//         'User-Agent' => request()->userAgent() ?: 'MyJobAlerts/1.0',
//         'Accept' => 'application/json',
//     ])->get(config('services.careerjet.url'), [
//         'locale_code' => 'en_IN',
//         'keywords' => $company,
//         'location' => '',
//         'page' => 1,
//         'page_size' => 100,
//         'sort' => 'date',
//         'fragment_size' => 2000,
//         'user_ip' => request()->ip(),
//         'user_agent' => request()->userAgent() ?: 'MyJobAlerts/1.0',
//     ]);

//     if (!$response->successful()) {
//         return response()->json([
//             'success' => false,
//             'status' => $response->status(),
//             'message' => $response->json('data.error', 'CareerJet API request failed'),
//         ], $response->status());
//     }

//     $jobs = collect($response->json('jobs', []))
//         ->filter(function ($job) use ($company) {
//             return strcasecmp(
//                 trim($job['company'] ?? ''),
//                 trim($company)
//             ) === 0;
//         })
//         ->values();

//     return response()->json([
//         'success' => true,
//         'company' => $company,
//         'total' => $jobs->count(),
//         'jobs' => $jobs,
//     ]);
// });