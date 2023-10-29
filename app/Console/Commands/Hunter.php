<?php

namespace App\Console\Commands;

use App\Events\CryptorankDataReceived;
use App\Services\BotSender;
use App\Services\Filters\BotFilter;
use App\Services\Processors\DataProcessor;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command as CommandAlias;

class Hunter extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hunter:go
                            {--time=600 : Go session every time}
                            {--percent=5 : Change price both way minimum that value will send bot message}
                            {--volume=10 : Rise up volume above that value will send bot message}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Go for a hunt';
    
    /**
     * @var false
     */
    private bool $shouldKeepRunning = true;
    
    /**
     * @var DataProcessor
     */
    private DataProcessor $dataProcessor;
    
    /**
     * @var BotSender
     */
    private BotSender $botSender;
    
    /**
     * @var BotFilter
     */
    private BotFilter $botFilter;
    
    /**
     * @param DataProcessor $dataProcessor
     * @param BotSender     $botSender
     * @param BotFilter     $botFilter
     */
    public function __construct(DataProcessor $dataProcessor, BotSender $botSender, BotFilter $botFilter)
    {
        parent::__construct();
        $this->dataProcessor = $dataProcessor;
        $this->botSender = $botSender;
        $this->botFilter = $botFilter;
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
        $volume = $this->option('volume');
        
        $this->line('Starting with options Time:' . $time . 's,  Percent:' . $percent . '%');
        
        $this->trap(SIGTERM, fn() => $this->shouldKeepRunning = false);
        while ($this->shouldKeepRunning) {
            $this->line('Getting data');
            try {
                $result = $this->dataProcessor->processCryptoRank();
                $this->info('Status:' . $result['status']['code'] . " Costs:" . $result['status']['creditsCost']);
                
                // send only items that accomplish filter conditions
                $filteredSd = $this->botFilter->filterSessionData($result['sessionData'], $percent, $volume);
                $this->botSender->sendSessionData($filteredSd);
            } catch (\Throwable $e) {
                throw new \RuntimeException($e->getMessage());
            }
            
            if ($result['sessionData']->getSymbols()->count() > 0) {
                // prepate watchlist
                CryptorankDataReceived::dispatch();
            }
            
            // wait progress bar
            $this->progressBar($time);
        }
        
        return CommandAlias::SUCCESS;
    }
}
