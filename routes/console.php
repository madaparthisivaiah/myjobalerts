<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::command('careerjet:refresh-india-jobs')
//     ->dailyAt('00:00')
//     ->timezone('Asia/Kolkata')
//     ->runInBackground()
//     ->withoutOverlapping()
//     ->appendOutputTo(storage_path('logs/careerjet-refresh.log'));
    

Schedule::command('whatjobs:sync-india')
    ->everySixHours()
    ->withoutOverlapping();