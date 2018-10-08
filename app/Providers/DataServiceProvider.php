<?php

namespace App\Providers;

use App\Services\BlizzardService;
use App\Services\CharacterService;
use App\Services\RealmService;
use Illuminate\Support\ServiceProvider;

class DataServiceProvider extends ServiceProvider
{
    protected $defer = true;

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
        $this->app->singleton(CharacterService::class, function () {
            return new CharacterService(app()->make(BlizzardService::class));
        });

        $this->app->singleton(RealmService::class, function() {
            return new RealmService(app()->make(BlizzardService::class));
        });

    }

    public function provides()
    {
        return [CharacterService::class, RealmService::class];
    }


}
