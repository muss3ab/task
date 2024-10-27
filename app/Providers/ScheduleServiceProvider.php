<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\DeleteOldPosts;
use App\Jobs\FetchRandomUser;

class ScheduleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            
            // Schedule the jobs
            $schedule->job(new DeleteOldPosts)->daily();
            $schedule->job(new FetchRandomUser)->everyFourHours();
        });
    }
}
