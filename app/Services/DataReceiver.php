<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Config\Repository;

/**
 *
 */
class DataReceiver
{
    /**
     * @var Client
     */
    private Client $client;
    /**
     * @var Repository
     */
    private Repository $config;
    
    /**
     * @param Client $client
     * @param Repository $config
     */
    public function __construct(Client $client, Repository $config)
    {
        $this->client = $client;
        $this->config = $config;
    }
    
    /**
     * @param array $symbolList
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCryptoRankCurrencies(array $symbolList): array
    {
        $list = implode(",", $symbolList);
        $query = [
            'api_key' => $this->config->get('api.cryptorank_key'),
            //'sort' => '-rank',
            'limit' => count($symbolList),
            'symbols' => $list
        ];
        
        $t = $this->client->request('GET', 'currencies', ['query' => $query]);
        
        return (array)json_decode($t->getBody()->getContents());
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
