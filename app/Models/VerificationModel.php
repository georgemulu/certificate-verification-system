<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class VerificationModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function log(int $certificateId, string $verifiedBy, string $status): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO verification_logs(certificate_id, verified_by, status)
            VALUES (:certificate_id, :verified_by, :status)
        ");
        return $stmt->execute([
            ':certificate_id' => $certificateId,
            ':verified_by' => $verifiedBy,
            ':status' => $status,
        ]);
    }
}