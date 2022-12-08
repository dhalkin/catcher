<?php

namespace App\Listeners;

use App\Events\CryptorankDataReceived;

class AnalyzeCryptorankData
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CryptorankDataReceived  $event
     * @return void
     */
    public function handle(CryptorankDataReceived $event)
    {
        //
    }
}
