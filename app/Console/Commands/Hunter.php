<?php

namespace App\Console\Commands;

use App\Services\BotFilter;
use App\Services\BotSender;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use App\Services\DataProcessor;

class Hunter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hunter:go';
    
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
    
    
    private const ATTEMPTS = 12;
    public const SEC_BETWEEN_ATTEMPTS = 600;
    
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
     * @param BotSender $botSender
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

//        $this->trap(SIGTERM, fn() => $this->shouldKeepRunning = false);
//        while ($this->shouldKeepRunning) {
//
//        }
        
        // will do  max cycles
        for ($i = 1; $i <= self::ATTEMPTS; $i++) {
            
            $this->line('Getting data');
            try {
                $result = $this->dataProcessor->processCryptoRank();
                $this->info('Status:' . $result['status']['code'] . " Costs:" . $result['status']['creditsCost']);
                
                $filteredSd = $this->botFilter->filterOutputData($result['sessionData']);
                $this->botSender->sendSessionData($filteredSd);
                
            } catch (Throwable $e) {
                throw new \RuntimeException($e->getMessage());
            }
            
            // if last cycle, skip progress bar
            if ($i == self::ATTEMPTS) {
                continue;
            }
            
            // wait progress bar
            $this->progressBar();
        }
        
        $this->line('Stop working by end attempts');
        
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
