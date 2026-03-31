<?php

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $host       = $_ENV['DB_HOST']      ?? 'localhost';
        $port       = $_ENV['DB_PORT']      ?? '5432';
        $dbname     = $_ENV['DB_NAME']      ?? '';
        $user       = $_ENV['DB_USER']      ?? '';
        $password   = $_ENV['DB_PASSWORD']  ?? '';

        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";

        try{
            $this->connection = new PDO($dsn, $user, $password, [
                PDO:: ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
                PDO:: ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            // Temporary: show real error for debugging
            // Remove this before going to production
            die("Database connection failed.");
        }
    }

    public static function getInstance(): self
    {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}