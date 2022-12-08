<?php

namespace App\Services;

use App\Models\CryptorankObservation;
use Illuminate\Support\Carbon;
use Carbon\CarbonInterface;
use App\Entity\Bot\Symbol;

/**
 *
 */
class Analyzer
{
    /**
     * @param CryptorankObservation $currentObservation
     * @return null|Symbol
     */
    public function getCalculatedSymbol(CryptorankObservation $currentObservation): ?Symbol
    {
        //can do calculation only when history data exists
        $previous = $this->getPreviousObservation($currentObservation);
        if ($previous) {
            $botItem = new Symbol($currentObservation->symbol);
            $botItem->setTime($this->getDiffTime($currentObservation, $previous));
            
            // percent price change in a timer
            $botItem->setPricePercent($this->percentagePriceChange($currentObservation, $previous));
            
            // percent volume change in a timer
            $botItem->setVolumePercent($this->percentageVolumeChange($currentObservation, $previous));
            $botItem->setPrice($currentObservation->price);
        }
        
        return $botItem ?? null;
    }
    
    /**
     * @param CryptorankObservation $current
     * @return CryptorankObservation|null
     */
    private function getPreviousObservation(CryptorankObservation $current): ?CryptorankObservation
    {
        $historyData = CryptorankObservation::where('cryptorank_id', $current->cryptorank_id)
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->get();
        
        if ($historyData->count() == 1) {
            return $historyData->first();
        }
        
        return null;
    }
    
    /**
     * @param CryptorankObservation $current
     * @param CryptorankObservation $previous
     * @return float
     */
    private function percentagePriceChange(CryptorankObservation $current, CryptorankObservation $previous): float
    {
        $diff = $current->price - $previous->price;
        $onePercent = $previous->price / 100;
        
        return round($diff / $onePercent, 2);
    }
    
    /**
     * @param CryptorankObservation $current
     * @param CryptorankObservation $previous
     * @return float
     */
    private function percentageVolumeChange(CryptorankObservation $current, CryptorankObservation $previous): float
    {
        $diff = $current->volume24h - $previous->volume24h;
        $onePercent = $previous->volume24h / 100;
        
        return round($diff / $onePercent, 2);
    }
    
    /**
     * @param CryptorankObservation $current
     * @param CryptorankObservation $previous
     * @return string
     */
    public function getDiffTime(CryptorankObservation $current, CryptorankObservation $previous): string
    {
        $startTime = Carbon::parse($previous->date_time);
        $finishTime = Carbon::parse($current->date_time);
        
        $options = [
            'join' => '',
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];
        
        return $finishTime->diffForHumans($startTime, $options, true);
    }
}

