<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;
use App\Services\DataReceiver;
use App\Services\DataProcessor;
use App\Services\DataMapper;
use App\Services\Analyzer;
use App\Services\BotSender;
use DefStudio\Telegraph\Models\TelegraphChat;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, function ($app) {
            $config = $app->get('config');
            
            return new Client(
                [
                    'base_uri' => $config->get('api.cryptorank_url')
                ]
            );
        });
        
        $this->app->bind(DataReceiver::class, function () {
            return new DataReceiver(
                $this->app->make(Client::class),
                $this->app->get('config')
            );
        });
    
        $this->app->bind(BotSender::class, function () {
            return new BotSender(TelegraphChat::find(1));
        });
        
        $this->app->bind(DataProcessor::class, function () {
            return new DataProcessor(
                $this->app->make(DataMapper::class),
                $this->app->make(DataReceiver::class),
                $this->app->make(Analyzer::class)
            );
        });
        
    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(base_path('config/api.php'), 'api');
    }
}
