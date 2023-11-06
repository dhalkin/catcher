<?php

namespace App\Console\Commands\Binance;

use App\Services\DataReceiver;
use App\Services\Filters\BinanceFilter;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Carbon\CarbonImmutable;
use App\Console\Commands\BaseCommand;
use stdClass;

class UpdateSymbols extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binance:update-symbols';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update symbols list';
    
    
    public function __construct(
        private readonly DataReceiver $dataReceiver,
        private readonly BinanceFilter $binanceFilter
    ) {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle()
    {
        $date = CarbonImmutable::now();
        $date2 = Carbon::now();
        $date3 = $date->sub('24 hours');
        
        //get existed symbols
        $s = $this->dataReceiver->getBinanceSymbols();
        $result  = $this->binanceFilter->filterExchangeInfo($s);
        
        return Command::SUCCESS;
    }
}
