<?php

namespace App\Helpers;

class CertificateHelper
{
    public static function computeHash(
        string $serialNumber,
        string $ownerName,
        string $course,
        string $certificateType,
        string $issuedAt,
        int $institutionId
    ): string {
        $salt = $_ENV['CERT_HASH_SALT'] ?? '';
        $issuedAt = date('Y-m-d', strtotime($issuedAt));

        $payload = $salt
            . $serialNumber . '|'
            . $ownerName    . '|'
            . $course       . '|'
            . $certificateType . '|'
            . $issuedAt     . '|'
            . $institutionId;

        return hash('sha256', $payload);
    }
}