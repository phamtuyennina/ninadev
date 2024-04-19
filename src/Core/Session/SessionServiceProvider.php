<?php

namespace NINA\Core\Session;

use NINA\Core\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('session', function () {
            return $this->app->make(\NINA\Core\Session\Session::class);
        });
    }
}