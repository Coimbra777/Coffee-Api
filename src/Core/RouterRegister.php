<?php

namespace Src\Core;

class RouterRegister
{
    private Router $router;
    private Container $container;

    public function __construct(Router $router, Container $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function register(string $method, string $path, string $controllerClass, string $action, bool $authRequired = false)
    {
        $this->router->add($method, $path, function ($param = null) use ($controllerClass, $action) {
            $controller = $this->container->make($controllerClass);
            if ($param !== null) {
                $controller->$action($param);
            } else {
                $controller->$action();
            }
        }, $authRequired);
    }
}
