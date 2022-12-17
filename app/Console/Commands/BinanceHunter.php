<?php

namespace App\Console\Commands;

use App\Services\BotSender;
use App\Services\Filters\BinanceFilter;
use App\Services\Processors\BinanceProcessor;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

/**
 *
 */
class BinanceHunter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hunter-binance:go {--time=60} {--percent=2}';

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
        $this->line('Starting with options Time:'. $time .'s,  Percent:' .$percent . '%');
        
        $this->trap(SIGTERM, fn() => $this->shouldKeepRunning = false);
        while ($this->shouldKeepRunning) {
    
            $this->line('Getting data...');
            $sessionData = $this->binanceProcessor->processBinance();
            // filter
            $filteredData = $this->binanceFilter->filterSessionData($sessionData, $percent);
            //send
            $this->line('Sending...');
            $this->botSender->sendBinanceSessionData($filteredData, $percent);
    
            // wait progress bar
            $this->progressBar($time);
        }
        
     return Command::SUCCESS;
    }
    
    /**
     * @param int $time
     * @return void
     */
    private function progressBar(int $time): void
    {
        $this->line('Waiting:' . $time . 'sec');
        $pb = range(1, $time);
        $bar = $this->output->createProgressBar(count($pb));
        $bar->start();
        foreach ($pb as $number) {
            $bar->advance();
            sleep(1);
        }
        $bar->finish();
        $this->newLine(1);
    }
}
