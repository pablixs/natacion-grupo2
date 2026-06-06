<?php

class ClassModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getSpecialties(){
        try {
            $stmt = $this->db->prepare("SELECT * FROM specialties");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener especialidades: ' . $e->getMessage());
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
