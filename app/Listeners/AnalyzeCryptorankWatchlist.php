<?php

namespace App\Listeners;

use App\Events\CryptorankDataReceived;
use App\Models\CryptorankObservation;
use App\Services\Analyze\Analyzer;
use App\Services\BotSender;
use App\Services\Filters\BotFilter;
use Illuminate\Support\Collection;

class AnalyzeCryptorankWatchlist
{
    private const PRICE_UP = "\xE2\xAC\x86";
    private const PRICE_DOWN = "\xE2\xAC\x87";
    private const ROWS_COMPARE = 5;
    private const MIN_PRICE_CHANGE_PERCENT = 5;
    
    /**
     * @var BotSender
     */
    private BotSender $botSender;
    
    /**
     * @var BotFilter
     */
    private BotFilter $botFilter;
    
    /**
     * @var \App\Services\Analyze\Analyzer
     */
    private Analyzer $analyzer;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(BotSender $botSender, BotFilter $botFilter, Analyzer $analyzer)
    {
        $this->botSender = $botSender;
        $this->botFilter = $botFilter;
        $this->analyzer = $analyzer;
    }
    
    /**
     * Handle the event.
     *
     * @param  \App\Events\CryptorankDataReceived $event
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
        
        /**
 * @var CryptorankObservation $stop
*/
        /**
 * @var CryptorankObservation $start
*/
        $stop = $listAttempts->first();
        $start = $listAttempts->last();
        
        $listSymbols = CryptorankObservation::query()
            ->select(['symbol', 'cryptorank_id'])
            ->whereBetween('sessionTime', [$start->getSessionTime(), $stop->getSessionTime()])
            ->groupBy(['symbol', 'cryptorank_id'])
            ->orderBy('cryptorank_id')
            ->get();
        
        
        $resultData = [];
        /**
 * @var CryptorankObservation $symbol
*/
        foreach ($listSymbols as $symbol) {
            // collection for analyze  sorting asc
            $symbolData = CryptorankObservation::query()
                ->select('cryptorank_id', 'sessionTime', 'name', 'symbol', 'price')
                ->whereBetween('sessionTime', [$start->getSessionTime(), $stop->getSessionTime()])
                ->where('cryptorank_id', $symbol->getCryptorankId())
                ->orderBy('sessionTime')
                ->get();
            
            // only if price changed more then param
            $priceChanged = $this->analyzer->percentagePriceChange($symbolData->last(), $symbolData->first());
            if (abs($priceChanged) < self::MIN_PRICE_CHANGE_PERCENT) {
                continue;
            }
            
            $resultData[$symbol->getCryptorankId()] = $this->analyze(
                $symbolData,
                $symbol->getSymbol(),
                $priceChanged,
                $symbolData->first()->getName()
            );
        }
        
        $filtered = $this->botFilter->filterWatchlistData(new Collection($resultData));
        $this->botSender->sendWatchlistData($start->getSessionTime(), $stop->getSessionTime(), $filtered);
    }
    
    /**
     * @param  Collection $symbolData
     * @param  string     $symbol
     * @param  float      $priceChanged
     * @param  string     $name
     * @return array
     */
    private function analyze(Collection $symbolData, string $symbol, float $priceChanged, string $name): array
    {
        $res = [];
        $resIndicator = [];
        
        /**
 * @var CryptorankObservation $sd
*/
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
            'name' => $name,
            'symbol' => $symbol,
            'consistent' => count($r) == 1,
            'result' => implode(" ", $res),
            'priceChanged' => $priceChanged
        ];
    }
}
