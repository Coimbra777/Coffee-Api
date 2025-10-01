<?php

use Src\Core\Router;
use Src\Core\Container;
use Src\Core\RouterRegister;
use Src\Controllers\AuthController;
use Src\Controllers\UserController;
use Src\Controllers\CoffeeController;

return function (Router $router, Container $container): Router {

    $routes = new RouterRegister($router, $container);

    // Rotas Auth
    $routes->register("POST", "login", AuthController::class, "login", false);
    $routes->register("POST", "register", AuthController::class, "register", false);

    // Rotas User
    $routes->register("GET", "users", UserController::class, "index", true);
    $routes->register("GET", "users/[PARAM]", UserController::class, "show", true);
    $routes->register("PUT", "users/[PARAM]", UserController::class, "update", true);
    $routes->register("DELETE", "users/[PARAM]", UserController::class, "delete", true);

    // Rotas Coffee
    $routes->register("POST", "coffee/drink/[PARAM]", CoffeeController::class, "drink", true);
    $routes->register("GET", "coffee/history/[PARAM]", CoffeeController::class, "history", true);
    $routes->register("GET", "coffee/ranking/day/[PARAM]", CoffeeController::class, "rankingByDay", true);
    $routes->register("GET", "coffee/ranking/lastdays/[PARAM]", CoffeeController::class, "rankingLastDays", true);

    return $router;
};
