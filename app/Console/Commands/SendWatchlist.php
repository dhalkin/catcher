<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\CryptorankDataReceived;

class SendWatchlist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watchlist:go';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        
        CryptorankDataReceived::dispatch();
        
        return Command::SUCCESS;
    }
}
