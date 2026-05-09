<?php

class Coach {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getCoachesCount(){
        $sql = "SELECT COUNT(c.user_id) as coaches FROM coaches c WHERE deleted_at IS NULL";

        $stmt = $this->db->query($sql);

        $coachesCount = $stmt->fetchColumn();

        return $coachesCount;
    }

     public function createCoach(array $data) {
        $sql = "INSERT INTO coaches (user_id, first_name, last_name, phone, specialty) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            $data['user_id'],
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['specialty'],

        ]);
    }
}

?>