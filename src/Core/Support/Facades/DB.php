<?php
namespace NINA\Core\Support\Facades;
class DB extends Facade{
    protected static function getFacadeAccessor(): string
    {
        return 'db';
    }
}