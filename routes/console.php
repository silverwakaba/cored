<?php

use Illuminate\Support\Facades\Schedule;

// Use "everyminute" for immediate test

Schedule::command('app:cron-jwt-token-checker')->daily()->withoutOverlapping()->runInBackground();
