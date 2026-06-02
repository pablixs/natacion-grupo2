<?php

class Profile {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getAllDataByRole(int $role_id){
        try {
            $sql = "SELECT u.id, u.email, CONCAT(p.first_name, ' ' , p.last_name) as full_name, p.phone, p.birth_date FROM users u 
            INNER JOIN profiles p ON u.id = p.user_id 
            WHERE u.deleted_at IS NULL AND u.role_id = ? ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$role_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return new PDOException('Error al obtener los perfiles: ' . $e->getMessage());
        }
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