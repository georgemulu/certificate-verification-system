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
        $stmt = $this->db->query("SELECT id, name FROM institutions ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}