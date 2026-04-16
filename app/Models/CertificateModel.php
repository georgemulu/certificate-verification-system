<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class CertificateModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(
        string $ownerName,
        string $certificateType,
        string $certificateCode,
        int $uploadedBy,
        int $institutionId
    ): bool {
        $stmt = $this->db->prepare("
            INSERT INTO certificates
                (owner_name, certificate_type, certificate_code, uploaded_by, institution_id)
            VALUES
                (:owner_name, :certificate_type, :certificate_code, :uploaded_by, :institution_id)
        ");

        return $stmt->execute([
            ':owner_name' => $ownerName,
            ':certificate_type' => $certificateType,
            ':certificate_code' => $certificateCode,
            ':uploaded_by'  => $uploadedBy,
            ':institution_id' => $institutionId,
        ]);
    }

    public function codeExists(string $code): bool
    {
        $stmt = $this->db->prepare("
            SELECT id FROM certificates
            WHERE certificate_code =:code
            LIMIT 1
        ");

        $stmt->execute([':code' => $code]);
        return $stmt->fetch() !== false;
    }

    public function generateUniqueCode(): string
    {
        do {
            $code = 'CERT-' . strtoupper(bin2hex(random_bytes(4)));
        } while ($this->codeExists($code));

        return $code;
    }
}
