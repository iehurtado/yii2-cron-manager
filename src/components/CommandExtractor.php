<?php

namespace gaxz\crontab\components;

use ReflectionClass;

/**
 * Creates an array of commands from console controller actions
 */
class CommandExtractor
{
    /**
     * Collect commands from controllers
     */
    public function getCommands($source): array
    {
        return $this->parseMethods($this->getReflection($source));
    }

    /**
     * @param string $className
     */
    public function getReflection($className): Reflectionclass
    {
        return new ReflectionClass($className);
    }

    /**
     * Exctract console command strings from class methods 
     * @param ReflectionClass $reflection
     */
    public function parseMethods(ReflectionClass $reflection): array
    {
        $class = preg_replace("/Controller$/", '', $reflection->getShortName());

        $commands = [];

        foreach ($reflection->getMethods() as $method) {

            if (preg_match("/^action[0-9A-Z]/", $method->name)) {

                $action = preg_replace("/^action/", '', $method->name);

                $command = $this->hyphenize($class) . "/" . $this->hyphenize($action);

                $commands[$this->normalizeName($command)] = $command;
            }
        }

        return $commands;
    }

    /**
     * Split string by capital letters and concatinate with hyphens
     * @param string $string
     */
    public function hyphenize($string): string
    {
        $array = preg_split("/(?=[A-Z])/", lcfirst($string));

        return implode('-', $array);
    }

    /**
     * Format command name for humans
     * @param string $string
     */
    public function normalizeName($string): string
    {
        $array = explode('-', $string);

        return ucfirst(str_replace('/', ': ', implode(' ', $array)));
    }
}
