<?php

namespace Src\Core;

use Core\Response;
use Src\Middleware\AuthMiddleware;

class Router
{
    private $routes = [];
    private $callbacks = [];
    private $protectedRoutes = [];

    public function add($method, $route, $callback, $protected)
    {
        $route = ltrim($route, '/');
        $this->routes[] = strtoupper($method) . ':/' . $route;
        $this->callbacks[] = $callback;
        $this->protectedRoutes[] = $protected;

        return $this;
    }

    public function dispatch($uri)
    {
        $param = '';
        $callback = '';
        $protected = '';
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestMethod = $_POST['_method'] ?? $requestMethod;
        $uri = $requestMethod . ":/" . $uri;

        if (preg_match('#/([^/]+)$#', $uri, $matches)) {
            $param = $matches[1];
            if (substr_count($uri, '/') > 1) {
                $uri = preg_replace('#/([^/]+)$#', '/[PARAM]', $uri);
            }
        }

        $index = array_search($uri, $this->routes);

        if ($index !== false) {
            $callback = explode("::", $this->callbacks[$index]);
            $protected = $this->protectedRoutes[$index];
        }

        $class = $callback[0] ?? '';
        $method = $callback[1] ?? '';

        if (class_exists($class) && method_exists($class, $method)) {
            $instance = new $class();

            if ($protected) {
                AuthMiddleware::handle();
            }

            return call_user_func_array([$instance, $method], [$param]);
        }

        $this->notFound();
    }

    public function notFound()
    {
        (new Response())->notFound("Rota não encontrada.");
    }
}
