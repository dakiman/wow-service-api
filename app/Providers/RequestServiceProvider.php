<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ResponseFactory $responseFactory)
    {
        $responseFactory->macro('api', function($data = [], $statusCode = 500, $errors = []) use ($responseFactory) {
            $customFormat = [
                'data' => $data,
                'errors' => $errors
            ];
            return $responseFactory->make($customFormat, $statusCode);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, function() {
           return new Client([
               'base_uri' => 'https://eu.api.battle.net/wow/',
               'query' => [
                   'locale' => 'en_GB',
                   'apikey' => env('WOW_API_KEY')
               ]
           ]);
        });
    }
}
