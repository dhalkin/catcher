<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Carbon\CarbonImmutable;
use App\Models\CryptorankObservation;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyreport:go';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report for last 24 hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = CarbonImmutable::now();
        $date2 = Carbon::now();
        $date3 = $date->sub('24 hours');
        
        //get existed symbols on that period
        
        
        
        
        return Command::SUCCESS;
    }
}
