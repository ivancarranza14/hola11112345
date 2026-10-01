<?php

declare(strict_types=1);

namespace App\Nucleo;

final class Vista
{
    private const CARPETA_VISTAS = __DIR__ . '/../../vistas/';

    public static function render(string $vista, array $datos = []): void
    {
        $rutaVista = self::CARPETA_VISTAS . $vista . '.php';

        if (!is_file($rutaVista)) {
            http_response_code(500);
            echo 'Vista no encontrada: ' . e($vista);
            return;
        }

        extract($datos);

        $contenidoVista = function () use ($rutaVista, $datos): void {
            extract($datos);
            require $rutaVista;
        };

        require self::CARPETA_VISTAS . 'layout.php';
    }
}
