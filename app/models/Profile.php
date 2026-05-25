<?php

class Profile {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /**
     * Obtiene todos los nadadores con sus correos electrónicos e imagen de perfil.
     */
    public function getAllByRole(int $role_id) {
        // Agregamos s.profile_image a la consulta
        $sql = "SELECT p.*, u.email 
                FROM profiles p
                INNER JOIN users u ON p.user_id = u.id 
                WHERE p.deleted_at IS NULL AND u.role_id = $role_id";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta los datos personales vinculados a un user_id, incluyendo la imagen.
     * @param array $data ['user_id', 'first_name', 'last_name', 'phone', 'profile_image']
     */
    public function create(array $data) {
        // Agregamos profile_image al INSERT
        $sql = "INSERT INTO profiles (user_id, first_name, 
        last_name, phone, 
        specialty, birth_date, 
        profile_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            $data['user_id'],
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['specialty'] ?? null,
            $data['birth_date'],
            // Si no viene imagen, podemos pasar un null o el nombre por defecto
            $data['profile_image'] ?? 'default-profile.png' 
        ]);
    }

    

    

    
}