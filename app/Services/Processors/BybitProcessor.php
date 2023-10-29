<?php

namespace App\Services\Processors;

use App\Services\DataReceiver;
use Carbon\Carbon;

class BybitProcessor
{
    /**
     * @var DataReceiver
     */
    private DataReceiver $dataReceiver;
    
    public function __construct(DataReceiver $dataReceiver)
    {
        $this->dataReceiver = $dataReceiver;
    }
    
    
    public function process()
    {
        $time = Carbon::now();
    
        $response = $this->dataReceiver->getByBitSpotCurrencies();
    }
}
