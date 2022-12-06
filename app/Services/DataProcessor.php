<?php

namespace App\Services;

use App\Models\NomicsBinanceUsd;
use App\Models\Symbol;
use App\Models\CryptorankResults;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class DataProcessor
{
    /**
     * @var DataMapper
     */
    private DataMapper $dataMapper;
    
    /**
     * @var DataReceiver
     */
    private DataReceiver $dataReceiver;
    
    /**
     * @var Analyzer
     */
    private Analyzer $analyzer;
    
    /**
     * @param DataMapper $dataMapper
     * @param DataReceiver $dataReceiver
     * @param Analyzer $analyzer
     */
    public function __construct(DataMapper $dataMapper, DataReceiver $dataReceiver, Analyzer $analyzer)
    {
        $this->dataMapper = $dataMapper;
        $this->dataReceiver = $dataReceiver;
        $this->analyzer = $analyzer;
    }
    
    /**
     * @return array
     * @throws GuzzleException
     */
    public function processCryptoRank(): array
    {
        $processorResponse = [];
        $nomicsList = Symbol::select('symbol')->distinct()->pluck('symbol')->toArray();
        $response = $this->dataReceiver->getCryptoRankCurrencies($nomicsList);
        $analyze = null;
        
        foreach ($response['data'] as $cryptoRankItem) {
            // save all observation if exists in nomicslist
            if (in_array($cryptoRankItem->symbol, $nomicsList)) {
                $observation = $this->dataMapper->mapCryptorankItemToObservation($cryptoRankItem);
                
                // analyze
                $analyzeStr = $this->analyzer->complexPumpAnalyze($observation);
                if ($analyzeStr !== null) {
                    $analyze[$observation->symbol] = $analyzeStr;
        
                    // save result
                    $cryptoResults = new CryptorankResults();
                    $cryptoResults->setDateTime($observation->date_time);
                    $cryptoResults->setSymbol($observation->symbol);
                    $cryptoResults->setResult($analyzeStr);
                    $cryptoResults->save();
                }
                
                // save after analyse
                $observation->save();
            }
        }
        
        $processorResponse['status'] = (array)$response['status'];
        $processorResponse['analyze'] = $analyze ?? [];
        
        return $processorResponse;
    }
}
