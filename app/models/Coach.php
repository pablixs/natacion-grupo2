<?php

class Coach {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAllCoachs(){
        $sql = "SELECT cantidad = count(distinct u.id), s.*, u.email
                FROM coaches c 
                INNER JOIN users u ON c.user_id = u.id 
                WHERE c.deleted_at IS NULL";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>