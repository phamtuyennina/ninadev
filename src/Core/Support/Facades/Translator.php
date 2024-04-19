<?php
namespace NINA\Core\Support\Facades;

class Translator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'translator';
    }
}