<?php

class Log {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getLast5ActivityLog(){
        try {
            $stmt = $this->db->prepare('SELECT * FROM activity_log ORDER BY ts desc LIMIT 5');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return new PDOException('Error al obtener los logs: ' . $e->getMessage());
        }
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