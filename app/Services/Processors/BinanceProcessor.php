<?php

namespace App\Services\Processors;

use App\Entity\Bot\BinanceSessionData;
use App\Models\Symbol;
use App\Services\DataMapper;
use App\Services\DataReceiver;
use  App\Services\Analyze\BinanceAnalyze;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class BinanceProcessor
{
    /**
     * @var DataReceiver
     */
    private DataReceiver $dataReceiver;
    
    /**
     * @var BinanceAnalyze
     */
    private BinanceAnalyze $binanceAnalyze;
    
    /**
     * @var DataMapper
     */
    private DataMapper $dataMapper;
    
    /**
     * @param DataReceiver $dataReceiver
     * @param DataMapper $dataMapper
     * @param BinanceAnalyze $binanceAnalyze
     */
    public function __construct(DataReceiver $dataReceiver, DataMapper $dataMapper, BinanceAnalyze $binanceAnalyze)
    {
        $this->dataReceiver = $dataReceiver;
        $this->dataMapper = $dataMapper;
        $this->binanceAnalyze = $binanceAnalyze;
    }
    
    /**
     * @throws GuzzleException
     */
    public function processBinance(): BinanceSessionData
    {
        $time = Carbon::now();
        $nomicsList = Symbol::select('symbol')->distinct()->pluck('symbol')->toArray();
        
        $symbols = '[';
        foreach ($nomicsList as $s) {
            $symbols .= '"' . $s . 'USDT",';
            
        }
        $symbols = rtrim($symbols, ",");
        $symbols .= ']';
        
        $response = $this->dataReceiver->getBinanceCurrencies($symbols);
        $bSessionData = new BinanceSessionData();
        $bSessionData->setTime($time);
        
        foreach ($response as $binanceItem) {
            $observation = $this->dataMapper->mapBinanceItemToObservation($binanceItem, $time);
            
            //analyze
            $symbol = $this->binanceAnalyze->getCalculatedSymbol($observation);
            if ($symbol) {
                $bSessionData->addSymbol($symbol);
            }
        }
        
        return $bSessionData;
    }
}
