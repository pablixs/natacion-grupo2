<?php

class Log {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getLast5ActivityLog(){
        $stmt = $this->db->prepare('SELECT TOP(5) FROM activity_log ORDER BY ts desc');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function newActivityLog(string $type, string $subject){

        $sql = "INSERT INTO activity_log
                (type, subject, ts)
                VALUES (?, ?, NOW())
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$type, $subject]);
    }
}