<?php
namespace NINA\Core\Request;
use NINA\Core\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('request', function () {
            return Request::capture();
        });
    }
}