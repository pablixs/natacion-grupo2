<?php

class Coach {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getCoachesCount(){
        $sql = "SELECT COUNT(c.user_id) as coaches FROM profiles c WHERE deleted_at IS NULL";

        $stmt = $this->db->query($sql);

        $coachesCount = $stmt->fetchColumn();

        return $coachesCount;
    }

     public function createCoach(array $data) {
        $sql = "INSERT INTO profiles (user_id, first_name, last_name, phone, specialty, birth_date, profile_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            $data['user_id'],
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['specialty'],
            $data['birth_date'],
            $data['profile_image'],

        ]);
    }
}

?>