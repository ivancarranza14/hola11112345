<?php

declare(strict_types=1);

namespace App\Modelos;

final class Cliente
{
    public function __construct(
        private readonly int $id,
        private readonly string $nombre,
    ) {
    }

    public function obtenerId(): int
    {
        return $this->id;
    }

    public function obtenerNombre(): string
    {
        return $this->nombre;
    }
}
