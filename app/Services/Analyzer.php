<?php

namespace App\Services;

use App\Models\CryptorankObservation;
use Illuminate\Support\Carbon;
use Carbon\CarbonInterface;

/**
 *
 */
class Analyzer
{
    
    private const TRIGGER_GROW_PERCENT_PRICE = 5;
    private const TRIGGER_GROW_PERCENT_VOLUME_24H = 5;
    
    /**
     * @var BotSender
     */
    private BotSender $botSender;
    
    /**
     * @param BotSender $botSender
     */
    public function __construct(BotSender $botSender)
    {
        $this->botSender = $botSender;
    }
    
    /**
     * @param CryptorankObservation $currentObservation
     * @return null|string
     */
    public function complexPumpAnalyze(CryptorankObservation $currentObservation): ?string
    {
        $result = [];
        // base in received data ?
        $result[] = 'pCh24h  (' . $currentObservation->percentChange24h . ')';
        
        // base on history data
        $previous = $this->getPreviousObservation($currentObservation);
        if ($previous) {
            $time = $this->getDiffTime($currentObservation, $previous);
            $result[] = $time . ' >>';
            
            // percent price change in a timer
            $percentPriceChange = $this->percentagePriceChange($currentObservation, $previous);
            if ($previous->price < $currentObservation->price) {
                // bot here
                if ($percentPriceChange > self::TRIGGER_GROW_PERCENT_PRICE) {
                    $this->botSender->addMessage("Price up (" . $time . ") <strong>" . $percentPriceChange . "</strong>%");
                }
                $result[] = 'price up(' . $percentPriceChange . '%)';
            } else {
                $result[] = 'price down(' . $percentPriceChange . '%)';
            }
            
            // percent volume change in a timer
            $percentVolumeChange = $this->percentageVolumeChange($currentObservation, $previous);
            if ($previous->volume24h < $currentObservation->volume24h) {
                // bot here
                if ($percentVolumeChange > self::TRIGGER_GROW_PERCENT_VOLUME_24H) {
                    $this->botSender->addMessage("Volume24 up (" . $time . ") <strong>" . $percentVolumeChange . "</strong>%");
                }
                $result[] = 'volume24h up (' . $percentVolumeChange . '%)';
            } else {
                $result[] = 'volume24h down (' . $percentVolumeChange . '%)';
            }
            
            // go deep
            // get history
            
            
            // send bot stats
            $this->botSender->sendAndFlush($currentObservation->symbol);
        }
        
        return (count($result) > 0) ? implode(" | ", $result) : null;
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

