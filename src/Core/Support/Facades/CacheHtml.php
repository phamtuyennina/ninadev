<?php
namespace NINA\Core\Support\Facades;
/**
 * @method static checkUrlCache(string $path)
 * @method static checkFile(string $md5)
 * @method static get(string $md5)
 */
class CacheHtml extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'cachehtml';
    }
}