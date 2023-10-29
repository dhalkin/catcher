<?php

namespace App\Console\Commands;

use App\Services\BotSender;
use App\Services\Filters\BinanceFilter;
use App\Services\Processors\BinanceProcessor;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command as CommandAlias;

/**
 *
 */
class BinanceHunter extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hunter-binance:go
                            {--time=60 : Go session every time}
                            {--percent=2 : Change price above that percent will send bot message}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Binance hunter';
    
    /**
     * @var bool
     */
    private bool $shouldKeepRunning = true;
    
    /**
     * @var BinanceProcessor
     */
    private BinanceProcessor $binanceProcessor;
    
    /**
     * @var BinanceFilter
     */
    private BinanceFilter $binanceFilter;
    
    /**
     * @var BotSender
     */
    private BotSender $botSender;
    
    /**
     * @param BinanceProcessor $binanceProcessor
     * @param BinanceFilter $binanceFilter
     * @param BotSender $botSender
     */
    public function __construct(BinanceProcessor $binanceProcessor, BinanceFilter $binanceFilter, BotSender $botSender)
    {
        parent::__construct();
        $this->binanceProcessor = $binanceProcessor;
        $this->binanceFilter = $binanceFilter;
        $this->botSender = $botSender;
    }
    
    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle()
    {
        $time = $this->option('time');
        $percent = $this->option('percent');
        $this->line('Starting with options Time:' . $time . 's,  Percent:' . $percent . '%');
        
        $this->trap(SIGTERM, fn() => $this->shouldKeepRunning = false);
        while ($this->shouldKeepRunning) {
            
            $this->line('Getting data...');
            $sessionData = $this->binanceProcessor->processBinance();
            // filter
            $filteredData = $this->binanceFilter->filterSessionData($sessionData, $percent);
            
            // send filtered items to deep analyze
//            if ($filteredData->getSymbols()->count() > 0) {
//                GotBinanceFiltered::dispatch($filteredData->getSymbols()->first());
//            }
            
            //$this->call('test:deep');
            
            //send
            $this->line('Sending...');
            $this->botSender->sendBinanceSessionData($filteredData, $percent);
            
            // wait progress bar
            $this->progressBar($time);
        }
        
        return CommandAlias::SUCCESS;
    }
}
