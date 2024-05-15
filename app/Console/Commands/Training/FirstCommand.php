<?php

namespace App\Console\Commands\Training;

use Illuminate\Console\Command;
use App\Models\Symbol;

class FirstCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $s = Symbol::all();

        return Command::SUCCESS;
    }
}
