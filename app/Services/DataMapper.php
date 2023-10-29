<?php

namespace App\Services;

use App\Models\CryptorankObservation;
use App\Models\BinanceObservations;
use Carbon\Carbon;

/**
 *
 */
class DataMapper
{
    /**
     * @param  \stdClass $itemCryptorank
     * @param  string    $sessionTime
     * @return CryptorankObservation
     */
    public function mapCryptorankItemToObservation(
        \stdClass $itemCryptorank,
        string $sessionTime
    ): CryptorankObservation {
        $result = new CryptorankObservation();
        $result->cryptorank_id = $itemCryptorank->id;
        $result->sessionTime = Carbon::parse($sessionTime);
        $result->lastUpdated = Carbon::parse($itemCryptorank->lastUpdated);
        $result->name = $itemCryptorank->name;
        $result->symbol = $itemCryptorank->symbol;
        $result->circulatingSupply = $itemCryptorank->circulatingSupply ?? null;
        $result->totalSupply = $itemCryptorank->totalSupply ?? null;
        
        $result->price = $itemCryptorank->values->USD->price ?? null;
        $result->volume24h = $itemCryptorank->values->USD->volume24h ?? null;
        $result->percentChange24h = $itemCryptorank->values->USD->percentChange24h ?? null;
        
        return $result;
    }
    
    /**
     * @param  \stdClass $itemBinance
     * @param  string    $time
     * @return BinanceObservations
     */
    public function mapBinanceItemToObservation(\stdClass $itemBinance, string $time): BinanceObservations
    {
        $result = new BinanceObservations();
        $result->setSymbol($itemBinance->symbol);
        $result->setPrice($itemBinance->price);
        $result->setSessionTime($time);
        
        return $result;
    }
}
