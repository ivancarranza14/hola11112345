<?php

declare(strict_types=1);

namespace App\Modelos;

use DateTimeImmutable;

final class Transferencia
{
    public function __construct(
        private readonly int $id,
        private readonly int $cuentaOrigenId,
        private readonly int $cuentaDestinoId,
        private readonly string $valor,
        private readonly DateTimeImmutable $fecha,
    ) {
    }

    public function obtenerId(): int
    {
        return $this->id;
    }

    public function obtenerCuentaOrigenId(): int
    {
        return $this->cuentaOrigenId;
    }

    public function obtenerCuentaDestinoId(): int
    {
        return $this->cuentaDestinoId;
    }

    public function obtenerValor(): string
    {
        return $this->valor;
    }

    public function obtenerFecha(): DateTimeImmutable
    {
        return $this->fecha;
    }
}
