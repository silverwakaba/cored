<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:cron-jwt-token-checker')->everyminute()->withoutOverlapping()->runInBackground();