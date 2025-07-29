<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

require_once __DIR__ . '/../src/Core/Autoload.php';
Autoload::load(__DIR__ . '/../src');

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$router = new Router();
$router
    ->add("POST", "login", "AuthController::login", false)
    ->add("GET", "users", "Src\Controllers\UserController::index", false)
    ->add("POST", "users", "Src\Controllers\UserController:store", false)
    ->add("GET", "users/[PARAM]", "Src\Controllers\UserController:show", true)
    ->add("PUT", "users/[PARAM]", "Src\Controllers\UserController:update", true)
    ->add("DELETE", "users/[PARAM]", "Src\Controllers\UserController:destroy", true)
    ->add("POST", "users/[PARAM]/drink", "Src\Controllers\UserController:drink", true);

$router->dispatch($uri);
