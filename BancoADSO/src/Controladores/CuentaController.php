<?php

declare(strict_types=1);

namespace App\Controladores;

use App\Nucleo\ControladorBase;
use App\Nucleo\Vista;
use App\Servicios\ServicioCuenta;

final class CuentaController extends ControladorBase
{
    public function panelAccion(): void
    {
        $cuentaId = $this->idCuentaActual();
        $saldo = (new ServicioCuenta())->obtenerSaldo($cuentaId);

        Vista::render('cuenta/panel', ['saldo' => $saldo]);
    }
}
