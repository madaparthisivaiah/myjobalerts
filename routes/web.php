<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\JobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationController; 
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');

Route::get('/jobs-by-states', [LocationController::class, 'index'])->name('locations.index');
Route::get('/india-jobs/{state}', [JobController::class, 'jobsbystate'])->name('jobs.by-state');



// Route::get('/', function () {
//     return view('home');
// });

// Route::get('/site', function () {
//     return view('home');
// });


// Route::get('/test-careerjet', function () {

//     $response = Http::withBasicAuth(
//         config('services.careerjet.api_key'),
//         ''
//     )
//     ->withHeaders([
//         'Referer' => 'http://127.0.0.1:8000/',
//         'User-Agent' => request()->userAgent() ?: 'MyJobAlerts/1.0',
//         'Accept' => 'application/json',
//     ])
//     ->get(config('services.careerjet.url'), [
//         'locale_code' => 'en_IN',
//         'keywords' => 'software developer',
//         'location' => 'Hyderabad',
//         'page' => 1,
//         'page_size' => 10,
//         'sort' => 'date',
//         'fragment_size' => 5000,

//         // Required by CareerJet
//         'user_ip' => request()->ip(),
//         'user_agent' => request()->userAgent() ?: 'MyJobAlerts/1.0',
//     ]);

//     return response()->json([
//         'http_status' => $response->status(),
//         'success' => $response->successful(),
//         'data' => $response->json(),
//     ]);
// });

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