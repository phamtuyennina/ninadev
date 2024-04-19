<?php

namespace NINA\Core\Config;

use NINA\Core\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        date_default_timezone_set(config('app.timezone'));
    }
    public function register(): void
    {

    }
}