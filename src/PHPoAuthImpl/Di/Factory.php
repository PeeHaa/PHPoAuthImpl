<?php

namespace PHPoAuthImpl\Di;

class Factory
{
    private $cache = [];

    public function addService($service)
    {
        $this->cache[get_class($service)] = $service;
    }

    public function build($className)
    {
        $reflection = new \ReflectionClass($className);
        $arguments  = $reflection->getConstructor()->getParameters();

        if($arguments === null || count($arguments) == 0) {
            return new $className;
        }

        $parameters = [];

        foreach ($arguments as $argument) {
            if (!array_key_exists($argument->getClass()->name, $this->cache)) {
                $this->cache[$argument->getClass()->name] = $this->build($argument->getClass()->name);
            }

            $parameters[] = $this->cache[$argument->getClass()->name];
        }

        return $reflection->newInstanceArgs($parameters);
    }
}
