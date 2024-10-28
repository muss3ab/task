<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\DeleteOldPosts;
use App\Jobs\FetchRandomUser;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new FetchRandomUser)->everyFourHours();
Schedule::job(new DeleteOldPosts)->daily();


