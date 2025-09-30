<?php

use Src\Core\Router;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis do .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? "America/Sao_Paulo");
$GLOBALS['secretJWT'] = $_ENV['JWT_SECRET'] ?? '';

// Headers globais
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

// Cria o router
$router = new Router();

// Carrega as rotas da API
$routes = require __DIR__ . '/../src/routes/api.php';
$router = $routes($router);

// Despacha requisição
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$router->dispatch($uri);
