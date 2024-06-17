<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use AfricasTalking\SDK\AfricasTalking;

class AfricasTalkingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AfricasTalking::class, function ($app) {
            $username = env('maikolmutiso');
            $apiKey = env('299a0d3c2b773ee78639a5a9ec1f544b9fb0f297fb661984ddb73b747aac5a8a');
            return new AfricasTalking($username, $apiKey);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
