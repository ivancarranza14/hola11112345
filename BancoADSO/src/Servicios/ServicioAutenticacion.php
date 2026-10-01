<?php

declare(strict_types=1);

namespace App\Servicios;

use App\Modelos\Cuenta;
use App\Repositorios\RepositorioCuentas;
use App\Repositorios\RepositorioUsuarios;
use App\Servicios\Excepciones\CredencialesInvalidasException;

final class ServicioAutenticacion
{
    private readonly RepositorioCuentas $repositorioCuentas;
    private readonly RepositorioUsuarios $repositorioUsuarios;

    public function __construct()
    {
        $this->repositorioCuentas = new RepositorioCuentas();
        $this->repositorioUsuarios = new RepositorioUsuarios();
    }

    public function autenticar(string $numeroCuenta, string $clave): Cuenta
    {
        $cuenta = $this->repositorioCuentas->buscarPorNumeroCuenta($numeroCuenta);

        if ($cuenta === null) {
            throw new CredencialesInvalidasException('Número de cuenta o contraseña incorrectos.');
        }

        $usuario = $this->repositorioUsuarios->buscarPorCuentaId($cuenta->obtenerId());

        if ($usuario === null || !password_verify($clave, $usuario->obtenerClaveHash())) {
            throw new CredencialesInvalidasException('Número de cuenta o contraseña incorrectos.');
        }

        return $cuenta;
    }

    public function reconfirmarClave(int $cuentaId, string $clave): void
    {
        $usuario = $this->repositorioUsuarios->buscarPorCuentaId($cuentaId);

        if ($usuario === null || !password_verify($clave, $usuario->obtenerClaveHash())) {
            throw new CredencialesInvalidasException('Contraseña incorrecta.');
        }
    }
}
