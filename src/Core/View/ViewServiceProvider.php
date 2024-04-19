<?php

namespace NINA\Core\View;

use NINA\Core\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make('view')->setCanFunction(function($action, $subject = null) {
            return true;
        });
        $this->app->make('view')->setAnyFunction(function($action, $subject = null) {
            return true;
        });
        $errorArray = [];
        $errorCallback = function($key = null) use ($errorArray) {
            if (array_key_exists($key, $errorArray)) {
                return $errorArray[$key];
            }
            return false;
        };
        $this->app->make('view')->setErrorFunction($errorCallback);
    }
    public function register(): void
    {
        $this->app->singleton('view', function () {
            return new \NINA\Core\View\View($this->app);
        });
    }
}