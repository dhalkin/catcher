<?php

namespace App\Services\Filters;

use App\Entity\Bot\BinanceSessionData;
use App\Entity\Bot\BinanceSymbol;

class BinanceFilter
{
    
    private const ALARM_PERCENT = 2;
    
    /**
     * @param BinanceSessionData $binanceSessionData
     * @return BinanceSessionData
     */
    public function filterSessionData(BinanceSessionData $binanceSessionData): BinanceSessionData
    {
        $result = $binanceSessionData->getSymbols()->filter(function ($symbol) {
            /** @var BinanceSymbol $symbol */
            return $symbol->getChangePrice() > self::ALARM_PERCENT ||
                $symbol->getChangePrice() < -1 * abs(self::ALARM_PERCENT);
        });
        
        return $binanceSessionData->setSymbols($result);
    }
    
    
}
