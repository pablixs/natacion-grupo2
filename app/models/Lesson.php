<?php

class Lesson
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }



    public function getSwimmerHome(int $swimmerId)
    {
        try {
            // Clases activas del alumno
            $sql = "
            SELECT l.id as register_id, l.level, 
                GROUP_CONCAT(s.specialty SEPARATOR ', ') as especialidades, 
                l.first_day_of_week, l.second_day_of_week, 
                l.start_time, l.end_time, 
                p.last_name as last_name
            FROM bookings b
            INNER JOIN lessons l ON l.id = b.lesson_id
            INNER JOIN lessons_specialties ls ON ls.lesson_id = l.id
            INNER JOIN specialties s ON s.id = ls.specialty_id
            INNER JOIN users u ON u.id = l.coach_id
            INNER JOIN profiles p ON u.id = p.user_id
            WHERE b.swimmer_id = ? AND l.active = 1
            GROUP BY l.id
            ORDER BY FIELD(l.first_day_of_week, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'),
                     l.start_time ASC
        ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$swimmerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener home del alumno: ' . $e->getMessage());
            return [];
        }
    }


    public function getSpecialties()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM specialties");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener especialidades: ' . $e->getMessage());
            return [];
        }
    }

    public function getEnrolledLessonsByUserId(int $swimmerId)
    {
        try {
            $sql =
                "SELECT l.id as register_id, l.level, 
            GROUP_CONCAT(s.specialty SEPARATOR ', ') as especialidades, 
            l.first_day_of_week, l.second_day_of_week, 
            l.start_time, l.end_time, 
            p.last_name as last_name, 
            CONCAT(
                (SELECT COUNT(DISTINCT b2.swimmer_id) FROM bookings b2 WHERE b2.lesson_id = l.id), 
                '/', l.capacity
            ) as capacity, 
            l.active
        FROM lessons l 
        INNER JOIN lessons_specialties ls ON ls.lesson_id = l.id 
        INNER JOIN specialties s ON s.id = ls.specialty_id 
        INNER JOIN users u ON u.id = l.coach_id 
        INNER JOIN profiles p ON u.id = p.user_id 
        INNER JOIN bookings b ON b.lesson_id = l.id AND b.swimmer_id = ?
        GROUP BY l.id
        ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$swimmerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener clases para alumno: ' . $e->getMessage());
            return [];
        }
    }
    public function getLessonsForSwimmer(int $swimmerId)
    {
        try {
            $sql =
                "SELECT l.id as register_id, l.level, 
                GROUP_CONCAT(s.specialty SEPARATOR ', ') as especialidades, 
                l.first_day_of_week, l.second_day_of_week, 
                l.start_time, l.end_time, 
                p.last_name as last_name, 
                CONCAT(
                    (SELECT COUNT(DISTINCT b.swimmer_id) FROM bookings b WHERE b.lesson_id = l.id), 
                    '/', l.capacity
                ) as capacity, 
                l.active,
                (SELECT COUNT(*) FROM bookings b WHERE b.lesson_id = l.id AND b.swimmer_id = :swimmer_id) as is_enrolled
            FROM lessons l 
            INNER JOIN lessons_specialties ls ON ls.lesson_id = l.id 
            INNER JOIN specialties s ON s.id = ls.specialty_id 
            INNER JOIN users u ON u.id = l.coach_id 
            INNER JOIN profiles p ON u.id = p.user_id 
            GROUP BY l.id
        ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$swimmerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener clases para alumno: ' . $e->getMessage());
            return [];
        }
    }

    public function getLessons()
    {
        try {
            $stmt = $this->db->prepare("SELECT l.id as register_id, l.level, GROUP_CONCAT(s.specialty SEPARATOR ', ') as especialidades, l.first_day_of_week, l.second_day_of_week, l.start_time, l.end_time, p.last_name as last_name, 
            concat((select count(distinct b.swimmer_id) from bookings b where b.lesson_id = l.id), '/', l.capacity) as capacity, l.active  
            FROM lessons l inner join lessons_specialties ls on ls.lesson_id = l.id inner join specialties s on s.id = ls.specialty_id inner join users u on u.id = l.coach_id 
            inner join profiles p on u.id = p.user_id group by l.id");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener clases: ' . $e->getMessage());
            return [];
        }
    }

    public function createClass(array $fields)
    {
        try {
            $this->db->beginTransaction();

            // 1. Insertar la clase
            $stmt = $this->db->prepare('INSERT INTO lessons (coach_id, level, first_day_of_week, second_day_of_week, start_time, end_time, active, capacity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $fields['coach_id'],
                $fields['level'],
                $fields['first_day_of_week'],
                $fields['second_day_of_week'],
                $fields['start_time'],
                $fields['end_time'],
                $fields['active'],
                $fields['capacity']
            ]);

            $lessonId = $this->db->lastInsertId();

            $stmtSpec = $this->db->prepare('INSERT INTO lessons_specialties (lesson_id, specialty_id) VALUES (?, ?)');
            foreach ($fields['specialties'] as $specialtyId) {
                $stmtSpec->execute([$lessonId, $specialtyId]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('Error al crear clase: ' . $e->getMessage());
            return ['error' => 'Error al crear la clase: ' . $e->getMessage()];
        }
    }

    
}
