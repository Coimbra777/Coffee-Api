<?php

use Src\Models\User;

class Router
{
    private $routes = [''];
    private $callbacks = [''];
    private $protectedRoutes = [''];

    public function add($method, $route, $callback, $protected)
    {
        $this->routes[] = strtoupper($method) . ':' . $route;
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

        // Check if route has a parameter
        if (substr_count($uri, "/") >= 3) {
            $param = substr($uri, strrpos($uri, "/") + 1);
            $uri = substr($uri, 0, strrpos($uri, "/")) . "/[PARAM]";
        }

        $index = array_search($uri, $this->routes);
        if ($index > 0) {
            $callback = explode("::", $this->callbacks[$index]);
            $protected = $this->protectedRoutes[$index];
        }

        $class = $callback[0] ?? '';
        $method = $callback[1] ?? '';

        if (class_exists($class)) {
            if (method_exists($class, $method)) {
                $instance = new $class();

                if ($protected) {
                    $auth = new User();
                    // if ($auth->verificar()) {
                    //     return call_user_func_array([$instance, $method], [$param]);
                    // } else {
                    //     echo json_encode(["error" => "Invalid token."]);
                    // }
                } else {
                    return call_user_func_array([$instance, $method], [$param]);
                }
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }

    public function notFound()
    {
        http_response_code(404);
        echo json_encode(["error" => "Route not found."]);
    }
}
