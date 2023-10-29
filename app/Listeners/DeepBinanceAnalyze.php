<?php

namespace App\Listeners;

use App\Events\GotBinanceFiltered;
use App\Services\DataReceiver;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DeepBinanceAnalyze
{
    private const PERIOD_SEC = 10;
    private const PERIOD_TIME = 6;
    
    /**
     * @var DataReceiver
     */
    private DataReceiver $dataReceiver;
    
    private string $pair;
    
    /**
     * @var false
     */
    private bool $shouldKeepPosition = true;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DataReceiver $dataReceiver)
    {
        $this->dataReceiver = $dataReceiver;
    }
    
    /**
     * Handle the event.
     * Base event of deep analyze selected symbol
     *
     * @param  \App\Events\GotBinanceFiltered $event
     * @return void
     * @throws GuzzleException
     */
    public function handle(GotBinanceFiltered $event)
    {
        $symbol = $event->getBinanceSymbol();
        $this->pair = $symbol->getName();
        $startPrice = $symbol->getCurrentPriceUSDT();
        $dataPrices = [];
        
        // design on buying
        $this->logging("Bying", $startPrice);
        
        // monitoring
        $loosingTime = 0;
        $middlePeriodPrice = 0;
        $startPeriodPrice = $startPrice;
        while ($this->shouldKeepPosition) {
            for ($i = 1; $i <= self::PERIOD_TIME; $i++) {
                // get current symbol price
                $t = $this->dataReceiver->getBinanceSymbolTicker($symbol->getName());
                $dataPrices[] = $t['price'];
                sleep(self::PERIOD_SEC);
            }
            
            $middlePeriodPrice = array_sum($dataPrices) / self::PERIOD_TIME;
            
            // compare with start price
            $diffPrice = $startPeriodPrice - $middlePeriodPrice;
            if ($diffPrice < 0) {
                // price growing up
                if ($loosingTime > 0) {
                    $loosingTime--;
                }
                
                $this->logging("Continue", $middlePeriodPrice);
            } else {
                // price going down
                $loosingTime++;
                $this->logging("LoosingTime:" . $loosingTime, $middlePeriodPrice);
            }
    
            $startPeriodPrice = $middlePeriodPrice;
            if ($loosingTime == 3 && $middlePeriodPrice < $startPrice) {
                // stop
                $this->shouldKeepPosition = false;
            }
            
            $dataPrices = [];
        }
        
        // sell
        $this->logging("Selling, start:" . $startPrice, $middlePeriodPrice);
    }
    
    /**
     * @param  string $message
     * @param  float  $price
     * @return void
     */
    private function logging(string $message, float $price)
    {
        Log::channel('deepAnalyze')->info($this->pair . " : " . $message . " : " . $price);
    }
}
