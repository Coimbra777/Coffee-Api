<?php

namespace Src\Core;

use ReflectionClass;
use Exception;

class Container
{
    private array $bindings = [];

    public function bind(string $abstract, callable $factory)
    {
        $this->bindings[$abstract] = $factory;
    }

    public function make(string $abstract)
    {
        if (isset($this->bindings[$abstract])) {
            return $this->bindings[$abstract]();
        }

        if (!class_exists($abstract)) {
            throw new Exception("Classe $abstract não encontrada");
        }

        $reflector = new ReflectionClass($abstract);
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $abstract;
        }

        $params = $constructor->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $type = $param->getType();
            if ($type && !$type->isBuiltin()) {
                $dependencies[] = $this->make($type->getName());
            } else {
                throw new Exception("Não é possível resolver {$param->getName()}");
            }
        }

        return $reflector->newInstanceArgs($dependencies);
    }
}
