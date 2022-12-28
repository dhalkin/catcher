<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Entity\Bot\BinanceSymbol;

class GotBinanceFiltered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    private BinanceSymbol $binanceSymbol;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BinanceSymbol $binanceSymbol)
    {
        $this->binanceSymbol = $binanceSymbol;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
    
    /**
     * @return BinanceSymbol
     */
    public function getBinanceSymbol(): BinanceSymbol
    {
        return $this->binanceSymbol;
    }
}
