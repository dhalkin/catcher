<?php

namespace App\Services\Filters;

use App\Entity\Bot\Filter\ChangePrice5Volume10;
use App\Entity\Bot\SessionData;
use App\Entity\Bot\Symbol;
use Illuminate\Support\Collection;

class BotFilter
{
    
    /**
     * @var ChangePrice5Volume10
     */
    private ChangePrice5Volume10 $filter;
    
    /**
     * @param ChangePrice5Volume10 $filter
     */
    public function __construct(ChangePrice5Volume10 $filter)
    {
        $this->filter = $filter;
    }
    
    /**
     * @param SessionData $sessionData
     * @return SessionData
     */
    public function filterSessionData(SessionData $sessionData): SessionData
    {
        $result = $sessionData->getSymbols()->filter(function ($symbol) {
            return $this->isSymbolForSend($symbol);
        });
        
       return $sessionData->setSymbols($result)->setFilter($this->filter);
    }
    
    /**
     * @param Collection $data
     * @return Collection
     */
    public function filterWatchlistData(Collection $data): Collection
    {
        return $data->filter(function ($symbol){
            return $symbol['consistent'] === true;
        });
    }
    
    /**
     * @param Symbol $symbol
     * @return bool
     */
    private function isSymbolForSend(Symbol $symbol): bool
    {
        if ((
                $symbol->getPricePercent() > $this->filter->getChangePrice() ||
                $symbol->getPricePercent() < -1 * abs($this->filter->getChangePrice()) ||
                $symbol->getVolumePercent() > $this->filter->getChangeVolume() ||
                $symbol->getCirculationPercent() > $this->filter->getChangeCirculation()
            ) || ($symbol->getSymbol() == 'BTC')) {
            return true;
        }
        
        return false;
    }
}
