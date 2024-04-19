<?php

namespace NINA\Core;
use Illuminate\Contracts\Container\BindingResolutionException;
use NINA\Core\Routing\NINARouter;
use NINA\Database\Capsule\Manager as Capsule;
use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\SimpleRouter\Event\EventArgument;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\Handlers\EventHandler;
use Pecee\SimpleRouter\Route\IGroupRoute;
use Pecee\SimpleRouter\Route\ILoadableRoute;
use Throwable;
use Illuminate\Container\Container;

class App
{
    use Singleton;
    private $routePaths = array();
    protected $capsule;
    private Container $container;
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->registerConfigProvider();
        new AliasLoader();
    }
    private function registerConfigProvider(): void{
        $this->container->singleton('config', function () {
            return new \NINA\Core\Config\Config();
        });
    }
    /**
     * @throws BindingResolutionException
     */
    private function loadConfiguration(): void
    {
        $configDir = $this->container->make('path.config');
        $configFiles = scandir($configDir);
        foreach ($configFiles as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            $filePath = $configDir . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
                $configKey = pathinfo($filePath, PATHINFO_FILENAME);
                $configArray = require $filePath;
                $this->container->make('config')->set($configKey, $configArray);
            }
        }
    }
    protected function loadConnet(): void
    {
        $defaultConnet = config('database.default');
        $this->capsule = new Capsule;
        $this->capsule->addConnection(config('database.connections.'.$defaultConnet));
        $this->capsule->setAsGlobal();
        $this->capsule->bootEloquent();
    }

    public function registerServiceProvider(): void
    {
        $providers = config('app.providers');
        if (!empty($providers)) {
            foreach ($providers as $provider) {
                $provider = new $provider($this->container);
                $provider->register();
            }
            foreach ($providers as $provider) {
                $provider = new $provider($this->container);
                $provider->boot();
            }
        }
    }
    private function registerCoreContainerAliases(): void{
        foreach ([
                 'filesystem' => [\Illuminate\Filesystem\FilesystemManager::class, \Illuminate\Contracts\Filesystem\Factory::class],
                 'filesystem.disk' => [\Illuminate\Contracts\Filesystem\Filesystem::class],
             ] as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->container->alias($key, $alias);
            }
        }
    }
    private function loadRoutes(): void{
        $basePath = substr(config('app.site_path'), 0, -1);
        $eventHandlerRouter = new EventHandler();
        $eventHandlerRouter->register(EventHandler::EVENT_ADD_ROUTE, function (EventArgument $event) use ($basePath) {
            $route = $event->route;
            if (!$event->isSubRoute) {
                return;
            }
            switch (true) {
                case $route instanceof ILoadableRoute:
                    $route->prependUrl($basePath);
                    break;
                case $route instanceof IGroupRoute:
                    $route->prependPrefix($basePath);
                    break;
            }
        });
        NINARouter::csrfVerifier(new \NINA\Middlewares\CsrfVerifier());
        NINARouter::setDefaultNamespace('\NINA\Controllers');
        NINARouter::enableMultiRouteRendering(true);
        $this->loadRoutesFrom('src/routes');
        foreach ($this->routePaths as $path) {
            include_directory($path, '.php');
        }
    }
    public function loadRoutesFrom($path): void
    {
        if (!in_array($path, $this->routePaths))
            $this->routePaths[] = $path;
    }

    /**
     * @throws HttpException
     * @throws NotFoundHttpException
     * @throws TokenMismatchException
     * @throws BindingResolutionException
     */
    public function run(): void
    {
        $this->loadConfiguration();
        $this->registerServiceProvider();
        $this->registerCoreContainerAliases();
        $this->loadConnet();
        $this->loadRoutes();
        NINARouter::start();

//        if (count(config('lang')) > 1 && config('app.langconfig')==='link') {
//            if (!array_key_exists(request()->segment(1), config('lang')) && (request()->segment(1)!='admin')) {
//                response()->redirect(request()->root().'/'.config('app.lang_default').'/');
//            }
//        }
        NINARouter::run();
    }
}
