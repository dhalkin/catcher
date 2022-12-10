<?php

namespace App\Listeners;

use App\Events\CryptorankDataReceived;
use App\Models\CryptorankObservation;
use App\Services\BotFilter;
use App\Services\BotSender;
use Illuminate\Support\Collection;

class AnalyzeCryptorankWatchlist
{
    
    private const PRICE_UP = "\xE2\xAC\x86";
    private const PRICE_DOWN = "\xE2\xAC\x87";
    private const ROWS_COMPARE = 5;
    
    /**
     * @var BotSender
     */
    private BotSender $botSender;
    
    /**
     * @var BotFilter
     */
    private BotFilter $botFilter;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(BotSender $botSender, BotFilter $botFilter)
    {
       $this->botSender = $botSender;
       $this->botFilter = $botFilter;
    }
    
    /**
     * Handle the event.
     *
     * @param \App\Events\CryptorankDataReceived $event
     * @return void
     */
    public function handle(CryptorankDataReceived $event)
    {
        //
        $listAttempts = CryptorankObservation::query()
            ->select('sessionTime')
            ->groupBy('sessionTime')
            ->orderBy('sessionTime', 'desc')
            ->limit(self::ROWS_COMPARE)
            ->get();
        
        if ($listAttempts->count() < self::ROWS_COMPARE) {
            return;
        }
        
        /** @var CryptorankObservation $stop */
        /** @var CryptorankObservation $start */
        $stop = $listAttempts->first();
        $start = $listAttempts->last();
        
        $listSymbols = CryptorankObservation::query()
            ->select(['symbol', 'cryptorank_id'])
            ->whereBetween('sessionTime', [$start->getSessionTime(), $stop->getSessionTime()])
            ->groupBy(['symbol', 'cryptorank_id'])
            ->orderBy('cryptorank_id')
            ->get();
        
        
        $resultData = [];
        /** @var CryptorankObservation $symbol */
        foreach ($listSymbols as $symbol) {
            
            // collection for analyze  sorting asc
            $symbolData = CryptorankObservation::query()
                ->select('*')
                ->whereBetween('sessionTime', [$start->getSessionTime(), $stop->getSessionTime()])
                ->where('cryptorank_id', $symbol->getCryptorankId())
                ->orderBy('created_at')
                ->get();
            
            
            $resultData[$symbol->getCryptorankId()] = $this->analyze($symbolData, $symbol->getSymbol());
        }
        
        $filtered = $this->botFilter->filterWatchlistData(new Collection($resultData));
        $this->botSender->sendWatchlistData($start->getSessionTime(), $stop->getSessionTime(), $filtered);
    }
    
    /**
     * @param Collection $symbolData
     * @param string $symbol
     * @return array
     */
    private function analyze(Collection $symbolData, string $symbol): array
    {
        $res = [];
        $resIndicator = [];
        
        /** @var CryptorankObservation $sd */
        foreach ($symbolData as $index => $sd) {
            // start on second row
            if ($index > 0) {
                $res[] = ($prevPrice > $sd->getPrice()) ? self::PRICE_DOWN : self::PRICE_UP;
                $resIndicator[] = ($prevPrice > $sd->getPrice()) ? '0' : '1';
            }
            $prevPrice = $sd->getPrice();
        }
        $r = array_unique($resIndicator);
        
        return [
            'symbol' => $symbol,
            'consistent' => count($r) == 1,
            'result' => implode(" ", $res)
        ];
    }
}
