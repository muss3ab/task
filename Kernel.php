<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\DeleteOldPosts;
use App\Jobs\FetchRandomUser;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new DeleteOldPosts)->daily();
        $schedule->job(new FetchRandomUser)->everyFourHours();
    }
}