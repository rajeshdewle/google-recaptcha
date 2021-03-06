<?php

namespace Chitranu\GoogleRecaptcha;

use ReCaptcha\ReCaptcha;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class GoogleRecaptchaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Load package config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('google-recaptcha.php'),
            ], 'config');
        }

        // Register validator
        Validator::extend('recaptcha', function ($attribute, $value, $parameters, $validator) {
            $recaptcha = new ReCaptcha(config('google-recaptcha.secret'));

            $response = $recaptcha->verify($value, request()->ip());

            return $response->isSuccess();
        }, 'Failed to verify recaptcha!');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'google-recaptcha');
    }
}
