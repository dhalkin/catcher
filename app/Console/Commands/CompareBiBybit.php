<?php

namespace App\Console\Commands;

use App\Services\BotSender;
use App\Services\Filters\BinanceFilter;
use App\Services\Processors\BinanceProcessor;
use GuzzleHttp\Exception\GuzzleException;

class CompareBiBybit extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compare';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare binanace - bybit';
    
    /**
     * @var BinanceProcessor
     */
    private BinanceProcessor $binanceProcessor;
    
    /**
     * @var BotSender
     */
    private BotSender $botSender;
    
    
    public function __construct(BinanceProcessor $binanceProcessor, BotSender $botSender)
    {
        parent::__construct();
        $this->binanceProcessor = $binanceProcessor;
        $this->botSender = $botSender;
    }
    
    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $this->line('Starting compare');
    
        $sessionBinData = $this->binanceProcessor->processBinance();
    }
}
