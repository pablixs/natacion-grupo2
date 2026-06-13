<?php

class Coach
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // --- SECCIÓN: BÚSQUEDA E IDENTIFICACIÓN ---

    /**
     * Busca un usuario por email.
     * @return array|bool Retorna los datos del usuario o false si no existe.
     */

    public function getCoachHome(int $coachId)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT l.id as register_id, l.level, 
                GROUP_CONCAT(s.specialty SEPARATOR ', ') as especialidades, 
                l.first_day_of_week, l.second_day_of_week, 
                l.start_time, l.end_time,
                (SELECT COUNT(DISTINCT b.swimmer_id) FROM bookings b WHERE b.lesson_id = l.id) as total_alumnos,
                l.capacity,
                l.active
            FROM lessons l 
            INNER JOIN lessons_specialties ls ON ls.lesson_id = l.id 
            INNER JOIN specialties s ON s.id = ls.specialty_id 
            WHERE l.coach_id = ?
            GROUP BY l.id
            ORDER BY FIELD(l.first_day_of_week, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'),
                     l.start_time ASC
        ");
            $stmt->execute([$coachId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener home del coach: ' . $e->getMessage());
            return [];
        }
    }

    public function getLessonStudents($lessonId)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT p.first_name, p.last_name, p.phone, p.profile_image
            FROM bookings b
            INNER JOIN profiles p ON p.user_id = b.swimmer_id
            WHERE b.lesson_id = ?
            ORDER BY p.last_name ASC
        ");
            $stmt->execute([$lessonId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener alumnos: ' . $e->getMessage());
            return [];
        }
    }

    public function validateToken(string $token)
    {
        $stmt = $this->db->prepare('SELECT 1 FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1');
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updatePasswordWhenSaveProfile(string $hashedNewPassword, int $user_id)
    {
        $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
        return $stmt->execute([$hashedNewPassword, $user_id]);
    }
}
