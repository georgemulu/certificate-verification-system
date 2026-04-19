<?php

namespace App\Models;

use App\Database\Database;

use PDO;

class DashboardModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    //Stats
    public function getVerifierStats(int $uploadedBy): array
    {
        $stmt = $this->db->prepare("
            SELECT
                COUNT(*)                                            AS total_uploaded,
                SUM(CASE WHEN is_revoked = true THEN 1 ELSE 0 END) AS total_revoked,
                MAX(issued_at)                                      AS last_upload
            FROM  certificates
            WHERE uploaded_by = :uploaded_by                      
        ");
        $stmt->execute([':uploaded_by' => $uploadedBy]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVerifierRecentCertificates(int $uploadedBy, int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT certificate_code, serial_number, owner_name,
                    certificate_type, course, issued_at, is_revoked
            FROM certificates
            WHERE uploaded_by = :uploaded_by
            ORDER BY uploaded_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':uploaded_by', $uploadedBy, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserStats(string $userEmail): array
    {
        $stmt = $this->db->prepare("
        
            SELECT
                COUNT(*)                                                AS total_verifications,
                SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END)    AS total_success,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END)     AS total_failed
            FROM verification_logs
            WHERE verified_by = :email
        ");
        $stmt->execute(['email' => $userEmail]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserRecentVerifications(string $userEmail, int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT vl.verified_at, vl.status,
                    c.certificate_code, c.owner_name, c.certificate_type
            FROM verification_logs vl
            JOIN certificates c ON c.id = vl.certificate_id
            WHERE vl.verified_by = :email
            ORDER BY vl.verified_at DESC
            LIMIT :limit  
        ");
        $stmt->bindValue(':email', $userEmail, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAdminStats(): array
    {
        $stmt = $this->db->query("
            SELECT
                (SELECT COUNT(*) FROM users)            AS total_users,
                (SELECT COUNT(*) FROM institutions)     AS total_institutions,
                (SELECT COUNT(*) FROM certificates)     AS total_certificates,
                (SELECT COUNT(*) FROM verification_logs) AS total_verifications
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAdminRecentActivity(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
            SELECT al.action, al.action_at,
                u.full_name as admin_name,
                t.full_name as target_name
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
