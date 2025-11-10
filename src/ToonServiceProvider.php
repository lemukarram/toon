<?php

namespace LeMukarram\Toon; // <-- UPDATED NAMESPACE

use Illuminate\Support\ServiceProvider;

class ToonServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('toon', function ($app) {
            return new ToonConverter();
        });
    }

    public function boot()
    {
        // No config file to publish for V1
    }
}