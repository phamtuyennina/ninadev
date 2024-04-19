<?php
namespace NINA\Core\Validator;
use NINA\Core\ServiceProvider;
class ValidationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $validator = $this->app->make('validator');
        $validator->setRules([
            'required',
            'min',
            'max',
            'number',
            'string',
            'file',
            'image',
            'video',
            'audio',
            'email',
            'unique'
        ]);
    }
    public function register(): void
    {
        $this->app->singleton('validator', function () {
            return new Validator();
        });
    }
}