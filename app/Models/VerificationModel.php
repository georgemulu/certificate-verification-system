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

    public function getByUser(string $userEmail): array
    {
        $stmt = $this->db->prepare("
            SELECT vl.id, vl.verified_at, vl.status,
                c.serial_number, c.owner_name, c.certificate_type, c.course,
                i.name AS institution_name
            FROM verification_logs vl
            JOIN certificates c ON c.id = vl.certificate_id
            JOIN institutions i ON i.id = c.institution_id
            WHERE vl.verified_by = :email
            ORDER BY vl.verified_at DESC
        ");
        $stmt->execute([':email' => $userEmail]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}