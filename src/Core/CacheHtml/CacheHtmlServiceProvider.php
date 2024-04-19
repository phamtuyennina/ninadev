<?php
namespace NINA\Core\CacheHtml;
use NINA\Core\ServiceProvider;
class CacheHtmlServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('cachehtml', function () {
            return new CacheHtml();
        });
    }
}