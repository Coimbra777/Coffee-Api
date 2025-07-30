<?php

namespace Src\Core;

use Src\Models\User;
use Src\Services\AuthService;

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

        if (substr_count($uri, "/") >= 2) {
            $param = substr($uri, strrpos($uri, "/") + 1);
            $uri = substr($uri, 0, strrpos($uri, "/")) . "/[PARAM]";
        }

        $index = array_search($uri, $this->routes);

        if ($index !== false) {
            $callback = explode("::", $this->callbacks[$index]);
            $protected = $this->protectedRoutes[$index];
        }

        $class = $callback[0] ?? '';
        $method = $callback[1] ?? '';

        if (class_exists($class)) {
            if (method_exists($class, $method)) {
                $instance = new $class();


                if ($protected) {
                    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                        // Token geralmente vem no header Authorization: Bearer <token>
                        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
                        $token = str_replace('Bearer ', '', $authHeader);

                        if (AuthService::verify($token)) {
                            return call_user_func_array([$instance, $method], [$param]);
                        } else {
                            http_response_code(401);
                            echo json_encode(["error" => "Invalid token."]);
                            return;
                        }
                    } else {
                        http_response_code(401);
                        echo json_encode(["error" => "Authorization token not provided."]);
                        return;
                    }
                } else {
                    return call_user_func_array([$instance, $method], [$param]);
                }
            } else {
                $this->notFound();
                return;
            }
        } else {
            $this->notFound();
            return;
        }
    }

    public function notFound()
    {
        http_response_code(404);
        echo json_encode(["error" => "Route not found."]);
    }
}
