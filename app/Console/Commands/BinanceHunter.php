<?php

namespace App\Console\Commands;

use App\Services\BotSender;
use App\Services\Filters\BinanceFilter;
use App\Services\Processors\BinanceProcessor;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

/**
 *
 */
class BinanceHunter extends Command
{
    
    public const SEC_BETWEEN_ATTEMPTS = 60;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hunter-binance:go';

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
        $this->trap(SIGTERM, fn() => $this->shouldKeepRunning = false);
        while ($this->shouldKeepRunning) {
    
            $sessionData = $this->binanceProcessor->processBinance();
            // filter
            $filteredData = $this->binanceFilter->filterSessionData($sessionData);
            //send
            $this->botSender->sendBinanceSessionData($filteredData);
    
            // wait progress bar
            $this->progressBar();
        }
        
     return Command::SUCCESS;
    }
    
    /**
     * @return void
     */
    private function progressBar(): void
    {
        $this->line('Waiting:' . self::SEC_BETWEEN_ATTEMPTS . 'sec');
        $pb = range(1, self::SEC_BETWEEN_ATTEMPTS);
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
