<?php

declare(strict_types=1);

namespace App\Modelos;

final class Usuario
{
    public function __construct(
        private readonly int $id,
        private readonly int $cuentaId,
        private readonly string $claveHash,
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

    public function obtenerClaveHash(): string
    {
        return $this->claveHash;
    }
}
