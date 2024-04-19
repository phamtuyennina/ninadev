<?php

namespace NINA\Core\Auth;

use NINA\Core\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('auth', function () {
            return $this->app->make(\NINA\Core\Auth\Authenticatable::class);
        });
    }
}