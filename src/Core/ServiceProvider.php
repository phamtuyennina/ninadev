<?php
namespace NINA\Core;
use NINA\Core\Support\DefaultProviders;

abstract class ServiceProvider
{
    protected $app;
    public function __construct($app){
        $this->app = $app;
    }
    public function boot(): void
    {}
    abstract public function register(): void;
    public static function defaultProviders(): DefaultProviders
    {
        return new DefaultProviders;
    }
}