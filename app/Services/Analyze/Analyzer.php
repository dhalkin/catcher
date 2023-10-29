<?php

namespace App\Services\Analyze;

use App\Entity\Bot\Symbol;
use App\Models\CryptorankObservation;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/**
 *
 */
class Analyzer
{
    /**
     * @param  CryptorankObservation $currentObservation
     * @return null|Symbol
     */
    public function getCalculatedSymbol(CryptorankObservation $currentObservation): ?Symbol
    {
        //can do calculation only when history data exists
        $previous = $this->getPreviousObservation($currentObservation);
        if ($previous) {
            $botItem = new Symbol($currentObservation->symbol);
            $botItem->setName($currentObservation->name);
            $botItem->setTime($this->getDiffTime($currentObservation, $previous));
            // percent price change in a timer
            $botItem->setPricePercent($this->percentagePriceChange($currentObservation, $previous));
            // percent volume change in a timer
            $botItem->setVolumePercent($this->percentageVolumeChange($currentObservation, $previous));
            // circulation supply  change in a timer
            $botItem->setCirculationPercent($this->getCirculatingSupplyChange($currentObservation, $previous));
            $botItem->setPrice($currentObservation->price);
        }
        
        return $botItem ?? null;
    }
    
    /**
     * @param  CryptorankObservation $current
     * @return CryptorankObservation|null
     */
    private function getPreviousObservation(CryptorankObservation $current): ?CryptorankObservation
    {
        $historyData = CryptorankObservation::where('cryptorank_id', $current->cryptorank_id)
            ->orderBy('sessionTime', 'desc')
            ->take(1)
            ->get();
        
        if ($historyData->count() == 1) {
            return $historyData->first();
        }
        
        return null;
    }
    
    /**
     * @param  CryptorankObservation $current
     * @param  CryptorankObservation $previous
     * @return float
     */
    public function percentagePriceChange(CryptorankObservation $current, CryptorankObservation $previous): float
    {
        $diff = $current->price - $previous->price;
        $onePercent = $previous->price / 100;
        
        return round($diff / $onePercent, 2);
    }
    
    /**
     * @param  CryptorankObservation $current
     * @param  CryptorankObservation $previous
     * @return float
     */
    private function percentageVolumeChange(CryptorankObservation $current, CryptorankObservation $previous): float
    {
        $diff = $current->volume24h - $previous->volume24h;
        $onePercent = $previous->volume24h / 100;
        
        return round($diff / $onePercent, 2);
    }
    
    /**
     * @param  CryptorankObservation $current
     * @param  CryptorankObservation $previous
     * @return float
     */
    private function getCirculatingSupplyChange(CryptorankObservation $current, CryptorankObservation $previous): float
    {
        $diff = $current->circulatingSupply - $previous->circulatingSupply;
        $onePercent = $previous->circulatingSupply / 100;
        
        return ($diff != 0) ? round($diff / $onePercent, 3) : 0;
    }
    
    /**
     * @param  CryptorankObservation $current
     * @param  CryptorankObservation $previous
     * @return string
     */
    private function getDiffTime(CryptorankObservation $current, CryptorankObservation $previous): string
    {
        $startTime = Carbon::parse($previous->lastUpdated);
        $finishTime = Carbon::parse($current->lastUpdated);
        
        $options = [
            'join' => '',
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];
        
        return $finishTime->diffForHumans($startTime, $options, true);
    }
}
