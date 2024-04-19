<?php
namespace NINA\Core\Support\Facades;
/**
 * @method static bool isset(string $key)
 * @method static void set(string $key,$value)
 * @method static void put(string $key,$value)
 * @method static bool has(string $key)
 * @method static void unset(string $key)
 * @method static \NINA\Core\Session\Session get(string $key)
 * @method static array storage()
 */
class Session extends Facade{
    protected static function getFacadeAccessor(): string
    {
        return 'session';
    }
}