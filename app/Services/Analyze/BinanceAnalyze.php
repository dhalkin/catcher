<?php

namespace App\Services\Analyze;

use App\Entity\Bot\BinanceSymbol;
use App\Models\BinanceObservations;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;


class BinanceAnalyze
{
    
    /**
     * @param BinanceObservations $binanceObservations
     * @return BinanceSymbol|null
     */
    public function getCalculatedSymbol(BinanceObservations $binanceObservations):? BinanceSymbol
    {
        // get previous time
        $issetPreviousData = Redis::exists('symbol:' .  $binanceObservations->getSymbol());
        
        //can do calculation only when history data exists
        if ($issetPreviousData) {
            $allPreviousData = Redis::hgetall('symbol:' .  $binanceObservations->getSymbol());
            
            $symbol = new BinanceSymbol();
            $symbol->setName($binanceObservations->getSymbol());
            $symbol->setChangePrice($this->percentagePriceChange($binanceObservations->getPrice(), $allPreviousData['price']));
            $symbol->setTime($this->getDiffTime($binanceObservations->getSessionTime(), $allPreviousData['session_time']));
            $symbol->setCurrentPriceUSDT($binanceObservations->getPrice());
        }
    
        //set new data
        Redis::hmset('symbol:' .  $binanceObservations->getSymbol(),
            [
                'price' => $binanceObservations->getPrice(),
                'session_time' => $binanceObservations->getSessionTime()
            ]);
        
        return $symbol ?? null;
    }
    
    /**
     * @param string $current
     * @param string $previous
     * @return string
     */
    private function getDiffTime(string $current, string $previous): string
    {
        $startTime = Carbon::parse($previous);
        $finishTime = Carbon::parse($current);
        
        $options = [
            'join' => '',
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];
        
        return $finishTime->diffForHumans($startTime, $options, true);
    }
    
    /**
     * @param string $current
     * @param string $previous
     * @return float
     */
    public function percentagePriceChange(string $current, string $previous): float
    {
        $diff = (float) $current - (float) $previous;
        $onePercent = (float) $previous / 100;
        
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
