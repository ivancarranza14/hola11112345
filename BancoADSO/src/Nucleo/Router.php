<?php

declare(strict_types=1);

namespace App\Nucleo;

final class Router
{
    public function despachar(string $ruta): void
    {
        $segmentos = array_filter(explode('/', trim($ruta, '/')), fn ($s) => $s !== '');
        $segmentos = array_values($segmentos);

        $nombreControlador = $segmentos[0] ?? 'sesion';
        $nombreAccion = $segmentos[1] ?? 'login';
        $parametros = array_slice($segmentos, 2);

        $claseControlador = 'App\\Controladores\\' . ucfirst($nombreControlador) . 'Controller';
        $metodoAccion = $nombreAccion . 'Accion';

        if (!class_exists($claseControlador) || !method_exists($claseControlador, $metodoAccion)) {
            http_response_code(404);
            Vista::render('errores/404');
            return;
        }

        $controlador = new $claseControlador();
        $controlador->$metodoAccion(...$parametros);
    }
}
