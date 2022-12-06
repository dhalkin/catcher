<?php

namespace App\Console\Commands;

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
    
    
    private const ATTEMPTS = 4;
    public const SEC_BETWEEN_ATTEMPTS = 600;
    
    /**
     * @var DataProcessor
     */
    private DataProcessor $dataProcessor;
    
    /**
     * @param DataProcessor $dataProcessor
     */
    public function __construct(DataProcessor $dataProcessor)
    {
        parent::__construct();
        $this->dataProcessor = $dataProcessor;
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
            $result = $this->dataProcessor->processCryptoRank();
            
            if ($result['status']['code'] == 200) {
                $this->info('Status:' . $result['status']['code'] . " Costs:" . $result['status']['creditsCost']);
                $this->drawAnalyzeResult($result['analyze']);
                
            } else {
                $this->warn('Status:' . $result['status']['code']);
            }
            
            // if last cycle, skip progress bar
            if ($i == self::ATTEMPTS){
                continue;
            }
            
            // wait progress bar
            $this->progressBar();
        }
    
        $this->line('Stop working by end attempts');
        
        return Command::SUCCESS;
    }
    
    /**
     * @param array $data
     * @return void
     */
    private function drawAnalyzeResult(array $data)
    {
        $this->line('Analyze data:');
        foreach ($data as $symbol => $val) {
            $this->line($symbol . " " . $val);
        }
    }
    
    /**
     * @return void
     */
    private function progressBar()
    {
        $this->line('Waiting:');
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
