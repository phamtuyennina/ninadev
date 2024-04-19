<?php

namespace NINA\Core\Hashing;

use NINA\Core\ServiceProvider;

class HashServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton('hash', function () {
            return new BcryptHasher();
        });
    }
}