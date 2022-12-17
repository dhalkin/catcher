<?php

namespace App\Services\Filters;

use App\Entity\Bot\BinanceSessionData;
use App\Entity\Bot\BinanceSymbol;

class BinanceFilter
{
    
    /**
     * @param BinanceSessionData $binanceSessionData
     * @param int $alarmChangePercent
     * @return BinanceSessionData
     */
    public function filterSessionData(BinanceSessionData $binanceSessionData, int $alarmChangePercent): BinanceSessionData
    {
        $result = $binanceSessionData->getSymbols()->filter(function ($symbol) use ($alarmChangePercent) {
            /** @var BinanceSymbol $symbol */
            return $symbol->getChangePrice() > $alarmChangePercent ||
                $symbol->getChangePrice() < -1 * abs($alarmChangePercent) ||
                $symbol->getName() == 'BTCUSDT';
        });
        
        return $binanceSessionData->setSymbols($result);
    }
}
