<?php

/**
 * Configuración de conexión a la base de datos usando PDO + Dotenv
 * Compatible con PHP 8+
 */

use Dotenv\Dotenv;

class Database
{
    private static $instance = null; // Singleton
    private $pdo;

    private function __construct()
    {
        // Cargar variables de entorno desde el raíz del proyecto
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();

        $host = $_ENV["DB_HOST"] ?? null;
        $db   = $_ENV["DB_NAME"] ?? null;
        $user = $_ENV["DB_USER"] ?? null;
        $pass = $_ENV["DB_PASS"] ?? null;

        try {
            $this->pdo = new PDO(
                "mysql:host={$host};dbname={$db};charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false
                ]
            );

        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    /**
     * Retorna instancia única PDO (Singleton)
     */
    public static function getConnection()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance->pdo;
    }
}

