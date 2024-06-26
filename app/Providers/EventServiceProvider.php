<?php

namespace App\Providers;

use App\Events\GotBinanceFiltered;
use App\Listeners\DeepBinanceAnalyze;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\CryptorankDataReceived;
use App\Listeners\AnalyzeCryptorankWatchlist;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        CryptorankDataReceived::class => [
            AnalyzeCryptorankWatchlist::class,
        ],
        GotBinanceFiltered::class => [
            DeepBinanceAnalyze::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
