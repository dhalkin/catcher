<?php

namespace App\Console\Commands;

use App\Entity\Bot\BinanceSymbol;
use App\Events\GotBinanceFiltered;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class TestDeepAnalyze extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:deep';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test deep';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $t = new BinanceSymbol();
        $t->setCurrentPriceUSDT(0.693);
        $t->setName("NEXOUSDT");
        $t->setChangePrice(1);
        $t->setTime(Carbon::now());
    
        GotBinanceFiltered::dispatch($t);
        
        return Command::SUCCESS;
    }
}
