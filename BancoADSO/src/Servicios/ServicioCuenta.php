<?php

declare(strict_types=1);

namespace App\Servicios;

use App\Nucleo\Conexion;
use App\Repositorios\RepositorioCuentas;
use App\Repositorios\RepositorioRetiros;
use App\Repositorios\RepositorioTransferencias;
use App\Servicios\Excepciones\CuentaDestinoInvalidaException;
use App\Servicios\Excepciones\CuentaNoEncontradaException;
use App\Servicios\Excepciones\SaldoInsuficienteException;
use App\Servicios\Excepciones\ValorInvalidoException;
use Throwable;

final class ServicioCuenta
{
    private readonly RepositorioCuentas $repositorioCuentas;
    private readonly RepositorioRetiros $repositorioRetiros;
    private readonly RepositorioTransferencias $repositorioTransferencias;
    private readonly ServicioAutenticacion $servicioAutenticacion;

    public function __construct()
    {
        $this->repositorioCuentas = new RepositorioCuentas();
        $this->repositorioRetiros = new RepositorioRetiros();
        $this->repositorioTransferencias = new RepositorioTransferencias();
        $this->servicioAutenticacion = new ServicioAutenticacion();
    }

    public function obtenerSaldo(int $cuentaId): string
    {
        $cuenta = $this->repositorioCuentas->buscarPorId($cuentaId);

        if ($cuenta === null) {
            throw new CuentaNoEncontradaException('La cuenta de la sesión ya no existe.');
        }

        return $cuenta->obtenerSaldo();
    }

    public function registrarRetiro(int $cuentaId, string $valor, string $claveConfirmacion): void
    {
        $this->servicioAutenticacion->reconfirmarClave($cuentaId, $claveConfirmacion);
        $this->validarValor($valor);

        $cuenta = $this->repositorioCuentas->buscarPorId($cuentaId);
        if ($cuenta === null) {
            throw new CuentaNoEncontradaException('La cuenta de la sesión ya no existe.');
        }

        if ((float) $cuenta->obtenerSaldo() < (float) $valor) {
            throw new SaldoInsuficienteException('El saldo disponible no alcanza para este retiro.');
        }

        $this->repositorioCuentas->descontarSaldo($cuentaId, $valor);
        $this->repositorioRetiros->registrar($cuentaId, $valor);
    }

    public function transferir(
        int $cuentaOrigenId,
        string $numeroCuentaDestino,
        string $valor,
        string $claveConfirmacion,
    ): void {
        $this->servicioAutenticacion->reconfirmarClave($cuentaOrigenId, $claveConfirmacion);

        $cuentaDestino = $this->repositorioCuentas->buscarPorNumeroCuenta($numeroCuentaDestino);
        if ($cuentaDestino === null) {
            throw new CuentaNoEncontradaException('La cuenta destino no existe.');
        }

        $this->validarValor($valor);

        $cuentaOrigen = $this->repositorioCuentas->buscarPorId($cuentaOrigenId);
        if ($cuentaOrigen === null) {
            throw new CuentaNoEncontradaException('La cuenta de la sesión ya no existe.');
        }

        if ((float) $cuentaOrigen->obtenerSaldo() < (float) $valor) {
            throw new SaldoInsuficienteException('El saldo disponible no alcanza para esta transferencia.');
        }

        if ($cuentaDestino->obtenerId() === $cuentaOrigen->obtenerId()) {
            throw new CuentaDestinoInvalidaException('La cuenta destino debe ser diferente a la cuenta origen.');
        }

        $pdo = Conexion::obtener();
        $pdo->beginTransaction();

        try {
            $this->repositorioCuentas->descontarSaldo($cuentaOrigenId, $valor);
            $this->repositorioCuentas->abonarSaldo($cuentaDestino->obtenerId(), $valor);
            $this->repositorioTransferencias->registrar($cuentaOrigenId, $cuentaDestino->obtenerId(), $valor);

            $pdo->commit();
        } catch (Throwable $error) {
            $pdo->rollBack();

            throw $error;
        }
    }

    private function validarValor(string $valor): void
    {
        if (!is_numeric($valor) || (float) $valor <= 0) {
            throw new ValorInvalidoException('El valor debe ser numérico y mayor que 0.');
        }
    }
}
