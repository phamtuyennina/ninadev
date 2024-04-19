<?php

namespace NINA\Core\Support\Facades;
/**
 * @method static guard(string $string)
 */
class Auth extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'auth';
    }
}