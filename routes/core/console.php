<?php

use Illuminate\Support\Facades\Schedule;

// JWT token refresh: run every 30 minutes for 1-hour tokens
// This ensures tokens are refreshed before expiration (refresh threshold: 15 minutes before expire)
// Tokens will be refreshed when they have 15 minutes or less remaining, giving enough margin for short-lived tokens
Schedule::command('app:cron-jwt-token-checker')->everyThirtyMinutes()->withoutOverlapping()->runInBackground();
