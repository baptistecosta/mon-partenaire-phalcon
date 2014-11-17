<?php

namespace BCosta;

class Di
{
    protected $cache;

    public function __construct()
    {
        $this->cache = [];
    }

    public function get($name)
    {
        if (array_key_exists($name, $this->cache)) {
            return $this->cache[$name];
        }

        $reflection = new \ReflectionClass($name);
        $constructor = $reflection->getConstructor();
        $params = $constructor ? $constructor->getParameters() : [];

        $args = [];
        foreach ($params as $param) {
            if (!$class = $param->getClass()) {
                throw new \Exception('Only class parameters are allowed for class constructors.');
            }
            $className = $class->getName();
            $args[] = $this->get($className);
        }

        $instance = $reflection->newInstanceArgs($args);
        $this->cache[$name] = $instance;

        return $instance;
    }
}
