<?php

declare(strict_types=1);

namespace App\Nucleo;

abstract class ControladorBase
{
    protected function requerirSesion(): void
    {
        if (!isset($_SESSION['cuenta_id'])) {
            header('Location: /index.php?ruta=sesion/login');
            exit;
        }
    }

    protected function idCuentaActual(): int
    {
        $this->requerirSesion();

        return (int) $_SESSION['cuenta_id'];
    }
}
