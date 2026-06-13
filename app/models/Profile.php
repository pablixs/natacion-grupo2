<?php

class Profile
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function updateSwimmerProfile(int $userId, array $data, $profileImage = null)
    {
        $sql = "UPDATE profiles SET 
                first_name = ?, 
                last_name = ?, 
                phone = ?, 
                birth_date = ?,
                updated_at = NOW()";

        $params = [
            $data['first_name'],
            $data['last_name'],
            $data['phone'],
            $data['birth_date'],
        ];

        if ($profileImage) {
            $sql .= ", profile_image = ?";
            $params[] = $profileImage;
        }

        $sql .= " WHERE user_id = ?";
        $params[] = $userId;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() === 0) {
            throw new Exception('No se detectaron cambios en el perfil.');
        }
    }

    public function getProfileImage($userId)
    {
        $stmt = $this->db->prepare("SELECT profile_image FROM profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['profile_image'] : null;
    }

    public function getSwimmerProfile(int $userId)
    {
        try {
            $stmt = $this->db->prepare("
            SELECT u.email, p.first_name, p.last_name, p.phone, 
                   p.specialty, p.birth_date, p.profile_image, p.created_at
            FROM users u
            INNER JOIN profiles p ON p.user_id = u.id
            WHERE u.id = ?
        ");
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error al obtener perfil: ' . $e->getMessage());
            return null;
        }
    }

    public function getAllDataByRole(int $role_id)
    {
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
    public function create(array $data)
    {
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
