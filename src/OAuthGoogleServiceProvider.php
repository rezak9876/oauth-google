<?php

namespace RezaK\OAuthGoogle;

use Illuminate\Support\ServiceProvider;

class OAuthGoogleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/oauth.php', 'oauth'
        );

        // Register Socialite configuration
        $this->app['config']->set('services.google', [
            'client_id' => config('oauth.providers.google.client_id'),
            'client_secret' => config('oauth.providers.google.client_secret'),
            'redirect' => config('oauth.providers.google.redirect'),
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/oauth.php' => config_path('oauth.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
