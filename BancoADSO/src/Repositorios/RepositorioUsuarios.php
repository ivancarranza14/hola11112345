<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Usuario;
use App\Nucleo\Conexion;
use PDO;

final class RepositorioUsuarios
{
    private readonly PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::obtener();
    }

    public function buscarPorCuentaId(int $cuentaId): ?Usuario
    {
        $consulta = $this->pdo->prepare(
            'SELECT id, cuenta_id, clave_hash
             FROM usuarios
             WHERE cuenta_id = :cuenta_id'
        );
        $consulta->execute(['cuenta_id' => $cuentaId]);
        $fila = $consulta->fetch();

        if ($fila === false) {
            return null;
        }

        return new Usuario(
            id: (int) $fila['id'],
            cuentaId: (int) $fila['cuenta_id'],
            claveHash: (string) $fila['clave_hash'],
        );
    }
}
