<?php

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            $dsn = sprintf(
                "pgsql:host=localhost;port=5432;dbname=cerificate_verification",
                $_ENV['DB_HOST'],
                $_ENV['DB_PORT'],
                $_ENV['DB_NAME'],
            );
            try {
                self ::$connection = new PDO(
                    $dsn, 
                    $_ENV['DB_USER'], 
                    $_ENV['DB_PASS'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                die('Database connection failed'. $e->getMessage());
            }
    }
    return self::$connection;
    }
}