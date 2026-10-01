<?php

declare(strict_types=1);

namespace App\Modelos;

final class Cuenta
{
    public function __construct(
        private readonly int $id,
        private readonly string $numeroCuenta,
        private readonly string $saldo,
        private readonly int $clienteId,
    ) {
    }

    public function obtenerId(): int
    {
        return $this->id;
    }

    public function obtenerNumeroCuenta(): string
    {
        return $this->numeroCuenta;
    }

    public function obtenerSaldo(): string
    {
        return $this->saldo;
    }

    public function obtenerClienteId(): int
    {
        return $this->clienteId;
    }
}
