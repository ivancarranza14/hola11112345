<?php

declare(strict_types=1);

namespace App\Nucleo;

use PDO;
use PDOException;

final class Conexion
{
    private static ?PDO $instancia = null;

    private function __construct()
    {
    }

    public static function obtener(): PDO
    {
        if (self::$instancia === null) {
            $config = require dirname(__DIR__, 2) . '/config/basedatos.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['puerto'],
                $config['nombre'],
                $config['charset']
            );

            try {
                self::$instancia = new PDO(
                    $dsn,
                    $config['usuario'],
                    $config['clave'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                throw new PDOException('No fue posible conectar a la base de datos: ' . $e->getMessage());
            }
        }

        return self::$instancia;
    }
}
