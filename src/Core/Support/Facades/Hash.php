<?php
namespace NINA\Core\Support\Facades;
/**
 * @method string make(string $value, array $options=[])
 * @method bool check(string $value, string $hashedValue, array $options = [])
 */
class Hash extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'hash';
    }
}