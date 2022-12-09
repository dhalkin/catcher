<?php

namespace App\Services;

use App\Models\Symbol;
use GuzzleHttp\Exception\GuzzleException;
use App\Entity\Bot\SessionData;
use Illuminate\Support\Collection;

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
    public function __construct(
        DataMapper   $dataMapper,
        DataReceiver $dataReceiver,
        Analyzer     $analyzer
    ) {
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
        $sessionData = new SessionData();
        
        if ($response['status']->code == 200) {
            foreach ($this->filterUnfilledData($response['data']) as $cryptoRankItem) {
                // save all observation if exists in nomicslist
                if (in_array($cryptoRankItem->symbol, $nomicsList)) {
                    $observation = $this->dataMapper->mapCryptorankItemToObservation(
                        $cryptoRankItem,
                        $response['status']->time
                    );
                    
                    // analyze
                    $symbol = $this->analyzer->getCalculatedSymbol($observation);
                    if ($symbol) {
                        $sessionData->addSymbol($symbol);
                    }
                    
                    // save after analyse
                    $observation->save();
                }
            }
        }
        $processorResponse['status'] = (array)$response['status'];
        $processorResponse['sessionData'] = $sessionData;
        
        return $processorResponse;
    }
    
    /**
     * @param array $allData
     * @return Collection
     */
    private function filterUnfilledData(array $allData): Collection
    {
        return (new Collection($allData))->filter(function ($data) {
            return isset($data->circulatingSupply) && $data->circulatingSupply > 0
                && isset($data->values->USD->volume24h) > 0 && $data->values->USD->volume24h > 0;
        });
    }
}
