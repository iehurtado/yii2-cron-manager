<?php

namespace gaxz\crontab\components;

use ReflectionClass;

/**
 * Creates an array of routes from console controller actions
 */
class RouteExtractor
{
    /**
     * Collect routes from controllers
     * @param string $source
     * @return array
     */
    public function getRoutes($source): array
    {
        return $this->parseMethods($this->getReflection($source));
    }

    /**
     * @param string $className
     * @return ReflectionClass
     */
    public function getReflection($className): Reflectionclass
    {
        return new ReflectionClass($className);
    }

    /**
     * Exctract routes from class methods and make it absolute
     * @param ReflectionClass $reflection
     * @return array
     */
    public function parseMethods(ReflectionClass $reflection): array
    {
        $class = preg_replace("/Controller$/", '', $reflection->getShortName());

        $routes = [];

        foreach ($reflection->getMethods() as $method) {

            if (preg_match("/^action[0-9A-Z]/", $method->name)) {

                $action = preg_replace("/^action/", '', $method->name);

                $route = "/" . $this->hyphenize($class) . "/" . $this->hyphenize($action);

                $routes[$route] = $this->normalizeName($route);
            }
        }

        return $routes;
    }

    /**
     * Split string by capital letters and concatinate with hyphens
     * @param string $string
     * @return string
     */
    public function hyphenize($string): string
    {
        $array = preg_split("/(?=[A-Z])/", lcfirst($string));

        return implode('-', $array);
    }

    /**
     * Format route name for humans
     * @param string $string
     * @return string
     */
    public function normalizeName($string): string
    {
        $array = explode('/', $string);

        foreach ($array as $key => $value) {
            if (empty($value)) {
                unset($array[$key]);
                continue;
            }

            $array[$key] = ucfirst($value);
        }

        return implode(' - ', $array);
    }
}
