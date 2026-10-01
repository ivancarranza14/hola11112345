<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Nucleo\Router;

session_start();

$ruta = $_GET['ruta'] ?? 'sesion/login';

$router = new Router();
$router->despachar((string) $ruta);
