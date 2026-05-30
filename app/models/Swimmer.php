<?php

class Swimmer
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Obtiene todos los nadadores con sus correos electrónicos e imagen de perfil.
     */
    public function getAll()
    {
        // Agregamos s.profile_image a la consulta
        $sql = "SELECT s.*, u.email 
                FROM swimmers s 
                INNER JOIN users u ON s.user_id = u.id 
                WHERE s.deleted_at IS NULL";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta los datos personales vinculados a un user_id, incluyendo la imagen.
     * @param array $data ['user_id', 'first_name', 'last_name', 'phone', 'profile_image']
     */
    public function create(array $data)
    {
        // Agregamos profile_image al INSERT
        $sql = "INSERT INTO swimmers (user_id, first_name, last_name, birth_date, phone, profile_image) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['user_id'],
            $data['first_name'],
            $data['last_name'],
            $data['birth_date'],
            $data['phone'],
            // Si no viene imagen, podemos pasar un null o el nombre por defecto
            $data['profile_image'] ?? 'default-profile.png'
        ]);
    }

    public function getSwimmersCount()
    {
        $sql = "SELECT COUNT(s.user_id) as alumnos FROM swimmers s WHERE deleted_at IS NULL";

        $stmt = $this->db->query($sql);

        $swimmersCount = $stmt->fetchColumn();

        return $swimmersCount;
    }
    //Buscamos segun id datos del usuario
    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM swimmers WHERE user_id = ? AND deleted_at IS NULL LIMIT 1"
        );

        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Enviamos datos actualizados
    public function updateProfile($userId, array $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE swimmers 
         SET first_name = ?, last_name = ?, phone = ?, birth_date = ?
         WHERE user_id = ?"
        );

        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['birth_date'],
            $userId
        ]);
    }
}
