<?php

namespace App\Providers;

use App\Services\BlizzardService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class BlizzardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BlizzardService::class, function () {
            return new BlizzardService(app()->make(Client::class));
        });
    }
}
