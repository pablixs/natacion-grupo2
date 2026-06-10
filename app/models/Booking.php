<?php

class Booking
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function alreadyBooked($swimmerId, $lessonId)
    {
        $sql = "SELECT id 
                FROM bookings 
                WHERE swimmer_id = ? AND lesson_id = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$swimmerId, $lessonId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($swimmerId, $lessonId)
    {
        $sql = "INSERT INTO bookings (swimmer_id, lesson_id, status, created_at)
                VALUES (?, ?, 'active', NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$swimmerId, $lessonId]);
    }

    public function countBySwimmer($swimmerId)
{
    $sql = "SELECT COUNT(*) as total
            FROM bookings
            WHERE swimmer_id = ?";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$swimmerId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}