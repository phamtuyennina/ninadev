<?php

namespace NINA\Core\View;

use Illuminate\Contracts\Container\BindingResolutionException;
use NINA\Core\Container;
use NINA\Core\Singleton;

class View extends BladeOne
{
    use Singleton;
    use BladeOneCache;
    protected string $viewPath;
    protected string $viewPathLayout;
    protected Container $app;
    protected string $viewCompiled;
    /**
     * @throws BindingResolutionException
     */
    public function __construct(Container $container)
    {
        $this->app = $container;
        $this->viewCompiled = $container->make('path.base') . '/compiled/';
        $this->setPathView();
        $this->templatePath = [$this->viewPath, $this->viewPathLayout];
        $this->compiledPath = $this->viewCompiled;
        $this->mode = BladeOne::MODE_AUTO;
    }
    /**
     * @throws BindingResolutionException
     */
    protected function setPathView(): void
    {
        $path = request()->segment(1);
        if ($path == 'admin') {
            $composer = new \NINA\Controllers\Admin\AllController();
            $this->composer('*', $composer);
            $this->viewPath = $this->app->make('path.views') . '/admin';
        } else if ($path == 'amp') $this->viewPath = $this->app->make('path.views') . '/amp';
        else {
            $composer = new \NINA\Controllers\Web\AllController();
            $this->composer('*', $composer);
            if (agent()->isMobile() || agent()->isTablet()) {
                $this->viewPath = $this->app->make('path.views') . '/mobile';
            } else {
                $this->viewPath = $this->app->make('path.views') . '/templates';
            }
        }
        $this->viewPathLayout = $this->app->make('path.views') . '/layout';
    }

    /**
     * @throws \Exception
     */
    public function view($path, $data = []): void
    {
        echo $this->run($path, $data);
    }
}
