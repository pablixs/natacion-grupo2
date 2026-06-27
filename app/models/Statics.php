<?php

class Statics {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getCountActivesSwimmers() {
        // Agregamos s.profile_image a la consulta
        $sql = "SELECT COUNT(*) as total
                FROM users u
                WHERE u.deleted_at IS NULL AND u.role_id = 3";
        
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    public function getCountActivesCoaches() {
        // Agregamos s.profile_image a la consulta
        $sql = "SELECT COUNT(*) as total
                FROM users u
                WHERE u.deleted_at IS NULL AND u.role_id = 2";
        
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    public function getActiveYears() {
        return (int) date('Y') - 2018 + 1;
    }
}