<?php
namespace NINA\Core\Intervention;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use NINA\Core\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('image', function () {
            return new ImageManager(new Driver());
        });
    }
}