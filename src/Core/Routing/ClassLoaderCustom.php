<?php
namespace NINA\Core\Routing;
use Pecee\SimpleRouter\ClassLoader\ClassLoader;
use ReflectionMethod;
class ClassLoaderCustom extends ClassLoader
{
    /**
     * @throws \ReflectionException
     */
    public function loadClassMethod($class, string $method, array $parameters): string
    {
        if(isset($parameters['language'])) unset($parameters['language']);
        $reflection = new ReflectionMethod($class, $method);
        foreach ($reflection->getParameters() as $param) {
            $type = $param->getType();
            if ($type && !$type->isBuiltin() && $type->getName() === \Illuminate\Http\Request::class) {
                $parameters[$param->name] = \Illuminate\Http\Request::capture();
            }
        }
        return (string)call_user_func_array([$class, $method], array_values($parameters));
    }
}