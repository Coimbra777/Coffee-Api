<?php

use Src\Core\Router;

return function (Router $router): Router {
    return $router
        ->add("POST", "login", "Src\Controllers\AuthController::login", false)
        ->add("POST", "register", "Src\Controllers\AuthController::register", false)

        ->add("GET", "users", "Src\Controllers\UserController::index", true)
        ->add("GET", "users/[PARAM]", "Src\Controllers\UserController::show", true)
        ->add("PUT", "users/[PARAM]", "Src\Controllers\UserController::update", true)
        ->add("DELETE", "users/[PARAM]", "Src\Controllers\UserController::delete", true)

        ->add("POST", "coffee/drink/[PARAM]", "Src\Controllers\CoffeeController::drink", true)
        ->add("GET", "coffee/history/[PARAM]", "Src\Controllers\CoffeeController::history", true)
        ->add("GET", "coffee/ranking/day/[PARAM]", "Src\Controllers\CoffeeController::rankingByDay", true)
        ->add("GET", "coffee/ranking/lastdays/[PARAM]", "Src\Controllers\CoffeeController::rankingLastDays", true);
};
