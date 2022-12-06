<?php

namespace App\Services;

use App\Models\CryptorankObservation;
use Carbon\Carbon;

/**
 *
 */
class DataMapper
{
    /**
     * @param \stdClass $itemCryptorank
     * @return CryptorankObservation
     */
    public function mapCryptorankItemToObservation(\stdClass $itemCryptorank): CryptorankObservation
    {
        $result = new CryptorankObservation();
        $result->cryptorank_id = $itemCryptorank->id;
        $result->date_time = Carbon::parse($itemCryptorank->lastUpdated);
        $result->name = $itemCryptorank->name;
        $result->symbol = $itemCryptorank->symbol;
        $result->type = $itemCryptorank->type;
        $result->circulatingSupply = $itemCryptorank->circulatingSupply ?? null;
        $result->totalSupply = $itemCryptorank->totalSupply ?? null;
        
        $result->price = $itemCryptorank->values->USD->price ?? null;
        $result->volume24h = $itemCryptorank->values->USD->volume24h ?? null;
        $result->percentChange24h = $itemCryptorank->values->USD->percentChange24h ?? null;
        
        return $result;
    }
}
