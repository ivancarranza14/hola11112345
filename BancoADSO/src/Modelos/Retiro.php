<?php

declare(strict_types=1);

namespace App\Modelos;

use DateTimeImmutable;

final class Retiro
{
    public function __construct(
        private readonly int $id,
        private readonly int $cuentaId,
        private readonly string $valor,
        private readonly DateTimeImmutable $fecha,
    ) {
    }

    public function obtenerId(): int
    {
        return $this->id;
    }

    public function obtenerCuentaId(): int
    {
        return $this->cuentaId;
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
