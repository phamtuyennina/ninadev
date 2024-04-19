<?php
namespace NINA\Core\Support\Facades;

/**
 * @method static get(string $key)
 */
class Config extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'config';
    }
}