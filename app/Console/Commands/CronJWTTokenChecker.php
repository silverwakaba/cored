<?php

namespace App\Console\Commands;

use App\Http\Controllers\Cron\JWTController;

use Illuminate\Console\Command;

class CronJWTTokenChecker extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cron-jwt-token-checker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check JWT token and refresh if near invalid date';

    /**
     * Execute the console command.
     */
    public function handle(JWTController $cron){
        $cron->refresh();
    }
}
