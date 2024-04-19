<?php
namespace NINA\Core;
class AliasLoader
{
    public function __construct()
    {
        spl_autoload_register([$this, 'aliasLoader']);
    }
    public function aliasLoader(string $class): bool
    {
        $alias = config('app.aliases');
        if (isset($alias[$class])) {
            return class_alias($alias[$class], $class);
        }
        return true;
    }
}