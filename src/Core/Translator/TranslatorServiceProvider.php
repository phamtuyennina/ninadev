<?php
namespace NINA\Core\Translator;
use NINA\Core\ServiceProvider;

class TranslatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('translator', function () {
            return $this->app->make(\NINA\Core\Translator\Translator::class);
        });
    }
}