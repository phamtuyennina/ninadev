<?php

namespace NINA\Core\Support\Facades;

class BreadCrumbs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'breadcrumbs';
    }
}