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

    public function create(string $fullName, string $email, string $password, int $institutionId): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("
            INSERT INTO users(full_name, email, password, institution_id)
            VALUES (:full_name, :email, :password, :institution_id)
        ");

        return $stmt->execute([
            ':full_name'    => $fullName,
            ':email'        => $email,
            ':password'     => $hashedPassword,
            ':institution_id'=> $institutionId,
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
}
