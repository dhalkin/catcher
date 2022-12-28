<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'base';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Only as base class for extended command';
    
    /**
     * @param int $time
     * @return void
     */
    protected function progressBar(int $time): void
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