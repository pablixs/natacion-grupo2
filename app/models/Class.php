<?php

class ClassModel
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }



    public function createClass(array $fields)
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO lessons (coach_id, level, specialities, first_day_of_week, second_day_of_week, start_time, end_time, active, capacity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            return $stmt->execute([
                $fields['coach_id'],
                $fields['level'],
                $fields['specialities'],
                $fields['first_day_of_week'],
                $fields['second_day_of_week'],
                $fields['start_time'],
                $fields['end_time'],
                $fields['active'],
                $fields['capacity']
            ]);
        } catch (PDOException $e) {
            // Manejo de errores
            error_log('Error al crear clase: ' . $e->getMessage());
            $error = ['error' => 'Error al crear la clase: ' . $e->getMessage()];
            return $error;
        }
    }
}
