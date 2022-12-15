<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Config\Repository;

class ClientFactory
{
    /**
     * @var Repository
     */
    private Repository $config;
    
    /**
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }
    
    /**
     * @return Client
     */
    public function createCryptorankClient(): Client
    {
        return new Client(
            [
                'base_uri' => $this->config->get('api.cryptorank_url')
            ]
        );
    }
    
    /**
     * @return Client
     */
    public function createBinanceClient(): Client
    {
        return new Client(
            [
                'base_uri' => $this->config->get('api.binance_url')
            ]
        );
    }
}
