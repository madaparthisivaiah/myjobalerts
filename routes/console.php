<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('careerjet:refresh-india-jobs')
    ->everyThreeHours()
    ->runInBackground()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/careerjet-refresh.log'));