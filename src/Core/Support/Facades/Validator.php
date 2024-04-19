<?php

namespace NINA\Core\Support\Facades;

class Validator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'validator';
    }
}