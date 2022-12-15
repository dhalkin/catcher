<?php

namespace App\Services\Analyze;

use App\Entity\Bot\BinanceSymbol;
use App\Models\BinanceObservations;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;


class BinanceAnalyze
{
    
    /**
     * @param BinanceObservations $binanceObservations
     * @return BinanceSymbol|null
     */
    public function getCalculatedSymbol(BinanceObservations $binanceObservations):? BinanceSymbol
    {
        //can do calculation only when history data exists
        $previous = $this->getPreviousObservation($binanceObservations);
        if ($previous) {
            $symbol = new BinanceSymbol();
            $symbol->setName($binanceObservations->getSymbol());
            $symbol->setChangePrice($this->percentagePriceChange($binanceObservations, $previous));
            $symbol->setTime($this->getDiffTime($binanceObservations, $previous));
        }
        
        return $symbol ?? null;
    }
    
    /**
     * @param BinanceObservations $current
     * @param BinanceObservations $previous
     * @return string
     */
    private function getDiffTime(BinanceObservations $current, BinanceObservations $previous): string
    {
        $startTime = Carbon::parse($previous->getSessionTime());
        $finishTime = Carbon::parse($current->getSessionTime());
        
        $options = [
            'join' => '',
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];
        
        return $finishTime->diffForHumans($startTime, $options, true);
    }
    
    /**
     * @param BinanceObservations $current
     * @param BinanceObservations $previous
     * @return float
     */
    public function percentagePriceChange(BinanceObservations $current, BinanceObservations $previous): float
    {
        $diff = $current->price - $previous->price;
        $onePercent = $previous->price / 100;
        
        return round($diff / $onePercent, 2);
    }
    
    /**
     * @param BinanceObservations $current
     * @return BinanceObservations|null
     */
    private function getPreviousObservation(BinanceObservations $current): ?BinanceObservations
    {
        $historyData = BinanceObservations::where('symbol', $current->getSymbol())
            ->orderBy('session_time', 'desc')
            ->take(1)
            ->get();
        
        if ($historyData->count() == 1) {
            return $historyData->first();
        }
        
        return null;
    }
}
