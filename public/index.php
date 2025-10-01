<?php

ob_start();

use Src\Core\Router;
use Dotenv\Dotenv;
use Src\Core\Container;
use Src\Core\Response;
use Src\Services\AuthService;

require __DIR__ . '/../vendor/autoload.php';

// Carrega variáveis do .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? "America/Sao_Paulo");
$GLOBALS['secretJWT'] = $_ENV['JWT_SECRET'] ?? '';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Cria o router
$router = new Router();

// container
$container = new Container();

$container->bind(Response::class, fn() => new Response());
$container->bind(AuthService::class, fn() => new AuthService($container->make(Response::class)));

// Carrega as rotas da API
$routes = require __DIR__ . '/../src/routes/api.php';
$router = $routes($router, $container);

// Despacha requisição
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$router->dispatch($uri);

ob_end_flush();
