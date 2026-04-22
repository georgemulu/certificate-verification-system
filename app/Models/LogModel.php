<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class LogModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getVerificationLogs(int $limit = 50): array
    {
        $stmt = $this->db->prepare("
            SELECT vl.id, vl.verified_by, vl.verified_at, vl.status,
                   c.serial_number, c.owner_name, c.certificate_type,
                   i.name AS institution_name
            FROM verification_logs vl
            JOIN certificates c ON c.id = vl.certificate_id
            JOIN institutions i ON i.id = c.institution_id
            ORDER BY vl.verified_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminLogs(int $limit = 50): array
    {
        $stmt = $this->db->prepare("
            SELECT al.id, al.action, al.action_at,
                   u.full_name AS admin_name,
                   t.full_name AS target_name
            FROM admin_logs al
            JOIN users u ON u.id = al.admin_id
            LEFT JOIN users t ON t.id = al.target_user
            ORDER BY al.action_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}