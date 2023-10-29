<?php

namespace App\Services\Filters;

use App\Entity\Bot\Filter\ChangePrice5Volume10;
use App\Entity\Bot\SessionData;
use App\Entity\Bot\Symbol;
use Illuminate\Support\Collection;

class BotFilter
{
    // ??
    protected const CHANGE_VOLUME_PERCENT  = 10;
    protected const CHANGE_CIRCULATION  = 1;
    
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
     * @param int $percent
     * @param int $volume
     *
     * @return SessionData
     */
    public function filterSessionData(SessionData $sessionData, int $percent, int $volume): SessionData
    {
        $result = $sessionData->getSymbols()->filter(
            function ($symbol) use ($percent, $volume) {
                return $this->isSymbolForSend($symbol, $percent, $volume);
            }
        );
        
        return $sessionData->setSymbols($result)->setFilter($this->filter);
    }
    
    /**
     * @param  Collection $data
     * @return Collection
     */
    public function filterWatchlistData(Collection $data): Collection
    {
        return $data->filter(
            function ($symbol) {
                return $symbol['consistent'] === true;
            }
        );
    }
    
    /**
     * @codingStandardsIgnoreStart
     * @param Symbol $symbol
     * @param int $percent
     * @param int $volume
     *
     * @return bool
     * @phpcsSuppress
     */
    private function isSymbolForSend(Symbol $symbol, int $percent, int $volume): bool
    {
        if ((            $symbol->getPricePercent() > $percent
            || $symbol->getPricePercent() < -1 * abs($percent)
            || $symbol->getVolumePercent() > $volume
            || $symbol->getCirculationPercent() > $this->filter->getChangeCirculation()) || ($symbol->getSymbol() == 'BTC')
        ) {
            return true;
        }
        
        return false;
    }
}
