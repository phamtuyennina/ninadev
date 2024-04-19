<?php

namespace NINA\Core\FileSystem;
use Illuminate\Filesystem\FilesystemManager;
use NINA\Core\ServiceProvider;
class FileSystemServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerNativeFilesystem();
        $this->registerFlysystem();
    }
    protected function registerNativeFilesystem(): void
    {
        $this->app->singleton('files', function () {
            return new FileSystem;
        });
    }
    protected function registerFlysystem(): void
    {
        $this->registerManager();
        $this->app->singleton('filesystem.disk', function ($app) {
            return $app['filesystem']->disk($this->getDefaultDriver());
        });
    }
    protected function registerManager(): void
    {
        $this->app->singleton('filesystem', function ($app) {
            return new FilesystemManager($app);
        });
    }
    protected function getDefaultDriver()
    {
        return $this->app['config']['filesystems.default'];
    }
}