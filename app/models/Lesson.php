<?php

class Lesson
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

   public function getAvailableLessons()
{
    $sql = "SELECT 
                l.id,
                l.level,
                l.day_of_week,
                l.start_time,
                l.end_time,
                l.capacity,
                COALESCE(p.first_name, u.email) AS coach_first_name,
                COALESCE(p.last_name, '') AS coach_last_name
            FROM lessons l
            LEFT JOIN users u ON u.id = l.coach_id
            LEFT JOIN profiles p ON p.user_id = u.id
            WHERE u.role_id = 2
            ORDER BY 
                FIELD(l.day_of_week, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'),
                l.start_time";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}