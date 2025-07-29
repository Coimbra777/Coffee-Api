<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

$GLOBALS['secretJWT'] = '123456';

require_once __DIR__ . '/../autoload.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

use Src\Core\Router;

$router = new Router();
$router
    ->add("POST", "login", "Src\Controllers\AuthController::login", false)
    ->add("POST", "register", "Src\Controllers\AuthController::register", false)
    ->add("GET", "users", "Src\Controllers\UserController::index", false)
    ->add("POST", "users", "Src\Controllers\UserController::store", false)
    ->add("GET", "users/[PARAM]", "Src\Controllers\UserController::show", true)
    ->add("PUT", "users/[PARAM]", "Src\Controllers\UserController::update", true)
    ->add("DELETE", "users/[PARAM]", "Src\Controllers\UserController::destroy", true)
    ->add("POST", "users/[PARAM]/drink", "Src\Controllers\UserController::drink", true);

$router->dispatch($uri);
