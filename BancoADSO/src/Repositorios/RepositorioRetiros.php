<?php

declare(strict_types=1);

namespace App\Repositorios;

use App\Modelos\Retiro;
use App\Nucleo\Conexion;
use DateTimeImmutable;
use PDO;

final class RepositorioRetiros
{
    private readonly PDO $pdo;

    public function __construct()
    {
        $this->pdo = Conexion::obtener();
    }

    public function registrar(int $cuentaId, string $valor): void
    {
        $consulta = $this->pdo->prepare(
            'INSERT INTO retiros (cuenta_id, valor, fecha) VALUES (:cuenta_id, :valor, NOW())'
        );
        $consulta->execute(['cuenta_id' => $cuentaId, 'valor' => $valor]);
    }

    public function listarPorCuenta(int $cuentaId): array
    {
        $consulta = $this->pdo->prepare(
            'SELECT id, cuenta_id, valor, fecha
             FROM retiros
             WHERE cuenta_id = :cuenta_id
             ORDER BY fecha DESC'
        );
        $consulta->execute(['cuenta_id' => $cuentaId]);

        $retiros = [];
        foreach ($consulta->fetchAll() as $fila) {
            $retiros[] = new Retiro(
                id: (int) $fila['id'],
                cuentaId: (int) $fila['cuenta_id'],
                valor: (string) $fila['valor'],
                fecha: new DateTimeImmutable((string) $fila['fecha']),
            );
        }

        return $retiros;
    }
}
