<?php

namespace NINA\Core\Cart;

use NINA\Core\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton('cart', function () {
            return $this->app->make(Cart::class);
        });
    }
}