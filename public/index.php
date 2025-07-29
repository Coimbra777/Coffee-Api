<?php

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

require_once __DIR__ . '/../src/Core/Autoload.php';
Autoload::load(__DIR__ . '/../src');

$uri = $_GET['url'] ?? '';

// Carrega o roteador
$router = new Router();
$router
    ->add("POST", "users", "UserController::store", false)
    ->add("GET", "users/[PARAM]", "UserController::show", true)
    ->add("GET", "users", "UserController::index", true)
    ->add("PUT", "users/[PARAM]", "UserController::update", true)
    ->add("DELETE", "users/[PARAM]", "UserController::destroy", true)
    ->add("POST", "login", "AuthController::login", false)
    ->add("POST", "users/[PARAM]/drink", "UserController::drink", true);

$router->dispatch($uri);
