<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use WebSocket\Client;
use WebSocket\ConnectionException;

class TestSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:socket';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    /**
     * @var bool
     */
    private bool $shouldKeepRunning = true;
    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $client = new Client("wss://stream.binance.com:443/ws/btcusdt@miniTicker");
        //$params = ['method' => 'SUBSCRIBE', 'params' => ['btcusdt@aggTrade'], 'id' => 999901];
        
        //$client->send(json_encode($params), 'json');
        
        $this->trap(
            SIGTERM,
            function ($client) {
                // handle the signal
            
                $client->close();
                $this->shouldKeepRunning = false;
            }
        );
        
        $i = 0;
        while ($this->shouldKeepRunning) {
            try {
                // get data
                $message = $client->receive();
                $this->line($message . "-" .$i);
                $i++;
            } catch (ConnectionException $e) {
                // Possibly log errors
                $this->line($e->getMessage());
            }
        }
        
        
        return Command::SUCCESS;
    }
}
