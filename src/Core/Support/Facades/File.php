<?php
namespace NINA\Core\Support\Facades;
/**
 * @method static exists(string $url)
 */
class File extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'files';
    }
}