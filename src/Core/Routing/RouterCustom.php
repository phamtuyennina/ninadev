<?php

namespace NINA\Core\Routing;
use NINA\Core\Routing\ClassLoaderCustom;
use Pecee\SimpleRouter\Router;
class RouterCustom extends Router
{
    public function reset(): void {
        parent::reset();
        $this->classLoader = new ClassLoaderCustom();
    }
}