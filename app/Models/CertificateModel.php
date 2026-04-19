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
        string $course,
        string $serialNumber,
        string $certificateCode,
        string $issuedAt,
        int $uploadedBy,
        int $institutionId
    ): bool {
        $stmt = $this->db->prepare("
            INSERT INTO certificates
                (owner_name, certificate_type, course, serial_number, certificate_code, issued_at, uploaded_by, institution_id)
            VALUES
                (:owner_name, :certificate_type,:course, :serial_number, :certificate_code, :issued_at, :uploaded_by, :institution_id)
        ");

        return $stmt->execute([
            ':owner_name' => $ownerName,
            ':certificate_type' => $certificateType,
            ':course' => $course,
            ':serial_number' => $serialNumber,
            ':certificate_code' => $certificateCode,
            ':issued_at' => $issuedAt,
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

    public function serialExistsforInstitution(string $serialNumber, int $institutionId): bool
    {
        $stmt = $this->db->prepare("
            SELECT id FROM certificates
            WHERE serial_number =:serial_number
            AND institution_id = :institution_id
            LIMIT 1
        ");
        $stmt->execute([
            ':serial_number' => $serialNumber,
            ':institution_id' => $institutionId,
        ]);
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
