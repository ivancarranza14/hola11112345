<?php

declare(strict_types=1);

namespace App\Controladores;

use App\Nucleo\ControladorBase;
use App\Nucleo\Vista;
use App\Repositorios\RepositorioRetiros;
use App\Servicios\Excepciones\CredencialesInvalidasException;
use App\Servicios\Excepciones\CuentaNoEncontradaException;
use App\Servicios\Excepciones\SaldoInsuficienteException;
use App\Servicios\Excepciones\ValorInvalidoException;
use App\Servicios\ServicioCuenta;

final class RetiroController extends ControladorBase
{
    public function formularioAccion(): void
    {
        $this->requerirSesion();
        Vista::render('retiros/formulario', []);
    }

    public function registrarAccion(): void
    {
        $cuentaId = $this->idCuentaActual();
        $valor = trim((string) ($_POST['valor'] ?? ''));
        $clave = (string) ($_POST['clave'] ?? '');

        try {
            (new ServicioCuenta())->registrarRetiro($cuentaId, $valor, $clave);
            $_SESSION['retiro_mensaje'] = 'Retiro realizado con éxito.';
        } catch (
            CredencialesInvalidasException
            | ValorInvalidoException
            | SaldoInsuficienteException
            | CuentaNoEncontradaException $error
        ) {
            $_SESSION['retiro_error'] = $error->getMessage();
        }

        header('Location: /index.php?ruta=retiro/resultado');
        exit;
    }

    public function resultadoAccion(): void
    {
        $this->requerirSesion();

        $mensaje = $_SESSION['retiro_mensaje'] ?? null;
        $error = $_SESSION['retiro_error'] ?? null;
        unset($_SESSION['retiro_mensaje'], $_SESSION['retiro_error']);

        Vista::render('retiros/resultado', ['mensaje' => $mensaje, 'error' => $error]);
    }

    public function historialAccion(): void
    {
        $cuentaId = $this->idCuentaActual();
        $retiros = (new RepositorioRetiros())->listarPorCuenta($cuentaId);

        $total = 0.0;
        foreach ($retiros as $retiro) {
            $total += (float) $retiro->obtenerValor();
        }

        Vista::render('retiros/historial', [
            'retiros' => $retiros,
            'cantidad' => count($retiros),
            'total' => number_format($total, 2, '.', ''),
        ]);
    }
}
