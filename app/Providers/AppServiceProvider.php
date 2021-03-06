<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Predis\Client;
use Predis\ClientInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ClientInterface::class, function (): Client {
            $config = config('database.redis.default');
            return new Client($config);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
