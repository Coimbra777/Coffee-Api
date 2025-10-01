<?php

namespace Src\Core;

use Src\Core\Response;
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
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestMethod = $_POST['_method'] ?? $requestMethod;
        $uri = $requestMethod . ":/" . $uri;

        // Detecta parâmetro na rota
        if (preg_match('#/([^/]+)$#', $uri, $matches)) {
            $param = $matches[1];
            if (substr_count($uri, '/') > 1) {
                $uri = preg_replace('#/([^/]+)$#', '/[PARAM]', $uri);
            }
        }

        $index = array_search($uri, $this->routes);

        if ($index === false) {
            return $this->notFound();
        }

        $callback = $this->callbacks[$index];
        $protected = $this->protectedRoutes[$index];

        // Middleware de proteção
        if ($protected) {
            AuthMiddleware::handle();
        }

        // Se for callable, chama direto (Closure, função anônima, etc)
        if (is_callable($callback)) {
            return $callback($param);
        }

        // Se for string "Controller::method"
        if (is_string($callback)) {
            [$class, $method] = explode('::', $callback);

            if (!class_exists($class) || !method_exists($class, $method)) {
                return $this->notFound();
            }

            $instance = new $class();
            return call_user_func_array([$instance, $method], [$param]);
        }

        // Caso inválido
        return $this->notFound();
    }

    public function notFound()
    {
        (new Response())->notFound("Rota não encontrada.");
    }
}
