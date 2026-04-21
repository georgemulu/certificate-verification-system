<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class InstitutionModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database:: getInstance() -> getConnection();
    }
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT id, name, email, created_at FROM institutions ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name, string $email): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO institutions (name,email)
            VALUES (:name, :email)
        ");

        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
        ]);
    }

    public function delete(int $id): bool 
    {
        $stmt = $this->db->prepare("
            DELETE FROM institutions WHERE id =:id
        ");
        return $stmt->execute([':id' => $id]);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT id, name, email, created_at
            FROM institutions
            WHERE id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}