<?php
namespace NINA\Core\Support\Facades;
use NINA\Core\Exceptions\NINAExceptions;
abstract class Facade
{
    protected static $app;
    protected static function getFacadeAccessor(): string
    {
        throw new NINAExceptions("Method " . __METHOD__ . " is not override.");
    }
    public static function __callStatic(string $method, array $arguments)
    {
        return app()->make(static::getFacadeAccessor())->$method(...$arguments);
    }
    public function __call(string $method, array $arguments)
    {
        return app()->make(static::getFacadeAccessor())->$method(...$arguments);
    }
    public static function defaultAliases()
    {
        return collect([
            "View" => \NINA\Core\Support\Facades\View::class,
            "Session" => \NINA\Core\Support\Facades\Session::class,
            "Translator" => \NINA\Core\Support\Facades\Translator::class,
            "Hash" => \NINA\Core\Support\Facades\Hash::class,
            "Auth" => \NINA\Core\Support\Facades\Auth::class,
            "Func" => \NINA\Core\Support\Facades\Func::class,
            "Flash" => \NINA\Core\Support\Facades\Flash::class,
            "Seo" => \NINA\Core\Support\Facades\Seo::class,
            "File" => \NINA\Core\Support\Facades\File::class,
            "BreadCrumbs" => \NINA\Core\Support\Facades\BreadCrumbs::class,
            "CacheHtml" => \NINA\Core\Support\Facades\CacheHtml::class,
            "DB" => \NINA\Core\Support\Facades\DB::class,
            'Config' => \NINA\Core\Support\Facades\Config::class,
            'Validator' => \NINA\Core\Support\Facades\Validator::class,
        ]);
    }
}
