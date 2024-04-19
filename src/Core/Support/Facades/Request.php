<?php
namespace NINA\Core\Support\Facades;
class Request extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'request';
    }
}