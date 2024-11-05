<?php

namespace RezaK\OAuthGoogle;

use Illuminate\Support\ServiceProvider;

class OAuthGoogleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/oauthgoogle.php', 'oauthgoogle'
        );

        // Register Socialite configuration
        $this->app['config']->set('services.google', [
            'client_id' => config('oauthgoogle.client_id'),
            'client_secret' => config('oauthgoogle.client_secret'),
            'redirect' => config('oauthgoogle.redirect'),
        ]);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/oauthgoogle.php' => config_path('oauthgoogle.php'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
