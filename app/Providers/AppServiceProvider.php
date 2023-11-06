<?php

namespace App\Providers;

use App\Services\Analyze\Analyzer;
use App\Services\ClientFactory;
use App\Services\DataMapper;
use App\Services\DataReceiver;
use App\Services\Processors\CryptoRankProcessor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            DataReceiver::class,
            function () {
                return new DataReceiver(
                    $this->app->make(ClientFactory::class),
                    $this->app->get('config')
                );
            }
        );
        
        $this->app->bind(
            CryptoRankProcessor::class,
            function () {
                return new CryptoRankProcessor(
                    $this->app->make(DataMapper::class),
                    $this->app->make(DataReceiver::class),
                    $this->app->make(Analyzer::class)
                );
            }
        );
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
