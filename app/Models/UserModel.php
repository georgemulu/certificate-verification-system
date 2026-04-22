<?php
namespace App\Models;

use App\Database\Database;
use PDO;

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this ->db = Database ::getInstance() -> getConnection();
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email =:email LIMIT 1");
        $stmt-> execute([':email' => $email]);
        return $stmt->fetch() !== false;
    }

    public function create(string $fullName, string $email, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("
            INSERT INTO users(full_name, email, password)
            VALUES (:full_name, :email, :password)
        ");

        return $stmt->execute([
            ':full_name'    => $fullName,
            ':email'        => $email,
            ':password'     => $hashedPassword,
        ]);
    }

    public function findByEmail(string $email): array | false
    {
        $stmt = $this->db->prepare("
            SELECT id, full_name, email, password, role, institution_id
            FROM users
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("
            SELECT u.id, u.full_name, u.email, u.role, u.created_at,
                   i.name AS institution_name
            FROM users u
            LEFT JOIN institutions i ON i.id = u.institution_id
            ORDER BY u.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRole(int $id, string $role): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users SET role = :role WHERE id = :id
        ");
        return $stmt->execute([':role' => $role, ':id' => $id]);
    }

    public function updateInstitution(int $id, ?int $institutionId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users SET institution_id = :institution_id WHERE id = :id
        ");
        return $stmt->execute([':institution_id' => $institutionId, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM users WHERE id = :id
        ");
        return $stmt->execute([':id' => $id]);
    }
}
