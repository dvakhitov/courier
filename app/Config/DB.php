<?php
namespace App\Config;

use PDO;

class DB {
    private static ?PDO $instance = null;

    public static function getConnection(): ?PDO
    {
        if (self::$instance === null) {
            $dsn = $_ENV['DB_DSN'] ?? 'pgsql:host=db;dbname=courierdb';
            $user = $_ENV['DB_USER'] ?? 'courier';
            $pass = $_ENV['DB_PASSWORD'] ?? 'courierpassword';

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                throw new \Exception('Ошибка подключения: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
