<?php

namespace App\Services;

use App\Services\ClientFactory;
use GuzzleHttp\Client;
use Illuminate\Config\Repository;

/**
 *
 */
class DataReceiver
{
    /**
     * @var ClientFactory
     */
    private ClientFactory $clientFactory;
    /**
     * @var Repository
     */
    private Repository $config;
    
    /**
     * @param ClientFactory $clientFactory
     * @param Repository    $config
     */
    public function __construct(ClientFactory $clientFactory, Repository $config)
    {
        $this->clientFactory = $clientFactory;
        $this->config = $config;
    }
    
    /**
     * @param  array $symbolList
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCryptoRankCurrencies(array $symbolList): array
    {
        $list = implode(",", $symbolList);
        $query = [
            'api_key' => $this->config->get('api.cryptorank_key'),
            //'sort' => '-rank',
            'limit' => count($symbolList) + 20, // found out that here is duplicate of symbols, different exchanges!
            'symbols' => $list
        ];
        
        $t = $this->clientFactory->createCryptorankClient()
            ->request('GET', 'currencies', ['query' => $query]);
        
        return (array)json_decode($t->getBody()->getContents());
    }
    
    /**
     * @param  array $symbolList
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBinanceCurrencies(string $symbolList): array
    {
        
        $query = [
            'symbols' => $symbolList
        ];
        
        $t = $this->clientFactory->createBinanceClient()
            ->request('GET', 'ticker/price', ['query' => $query]);
        
        if ($t->getStatusCode() == 200) {
            return (array)json_decode($t->getBody()->getContents());
        }
        
        throw new \RuntimeException("Unable to get binance");
    }
    
    /**
     * @param  string $symbol
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBinanceSymbolTicker(string $symbol): array
    {
        $query = [
            'symbol' => $symbol
        ];
    
        $t = $this->clientFactory->createBinanceClient()
            ->request('GET', 'ticker/price', ['query' => $query]);
    
        if ($t->getStatusCode() == 200) {
            return (array)json_decode($t->getBody()->getContents());
        }
    
        throw new \RuntimeException("Unable to get binance");
    }
    
    
    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getByBitSpotCurrencies(): array
    {
        $t = $this->clientFactory->createBybitClient()
            ->request('GET', 'v5/market/tickers');
    
        if ($t->getStatusCode() == 200) {
            return (array)json_decode($t->getBody()->getContents());
        }
    
        throw new \RuntimeException("Unable to get binance");
    }
    
    /**
     * @param  string $symbol
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBinanceSymbols(): array
    {
        $t = $this->clientFactory->createBinanceClient()
            ->request('GET', 'exchangeInfo', []);
        
        if ($t->getStatusCode() == 200) {
            return (array)json_decode($t->getBody()->getContents());
        }
        
        throw new \RuntimeException("Unable to get binance");
    }
    
    
    /**
     * @return array
     */
    public function mockCryptoRankCurrencies(): array
    {
        return [
            'data' => [],
            'meta' => [],
            'status' => [
                'time' => '',
                'success' => true,
                'code' => 200,
                'message' => 'OK',
                'responseTime' => 10,
                'creditCosts' => 2
            ]
        ];
    }
}
