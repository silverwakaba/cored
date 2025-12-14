<?php

namespace App\Console\Commands\Core;

use App\Http\Controllers\Core\Cron\JwtManagerController;

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
    public function handle(JwtManagerController $cron){
        $cron->refresh();
    }
}
