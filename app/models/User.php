<?php

class User {
    private $db;

    public function __construct( $pdo ) {
        $this->db = $pdo;
    }

    // --- SECCIÓN: BÚSQUEDA E IDENTIFICACIÓN ---

    /**
    * Busca un usuario por email.
    * @return array|bool Retorna los datos del usuario o false si no existe.
    */

    public function findByEmail(string $email ) {
        $stmt = $this->db->prepare( 'SELECT * FROM users WHERE email = ? AND deleted_at IS NULL LIMIT 1' );
        $stmt->execute( [ $email ] );
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    // Considerar mover este metodo a otro modelo que maneje la tabla de reset_passwords
    public function getUserIdAndTokenIdByToken(string $token){
        $stmt = $this->db->prepare( 'SELECT id as token_id, user_id FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1' );
        $stmt->execute( [ $token ] );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCountByRole(int $role_id){
        $sql = "SELECT COUNT(u.id) as total FROM users u WHERE deleted_at IS NULL AND u.role_id = $role_id";

        $stmt = $this->db->query($sql);

        $swimmersCount = $stmt->fetchColumn();

        return $swimmersCount;
    }

    // --- SECCIÓN: GESTIÓN DE CUENTA ---

    /**
    * Crea las credenciales de acceso para un nuevo usuario.
    * @param array $data [ 'email' => string, 'password' => string, 'role_id' => int ]
    */

    public function createUser( array $data ) {
        $hash = password_hash( $data[ 'password' ], PASSWORD_BCRYPT );
        // Usamos el role_id del array, o 3 ( Swimmer ) por defecto si no viene
        $roleId = $data[ 'role_id' ] ?? 3;
 

        $stmt = $this->db->prepare( 'INSERT INTO users (email, password, role_id, profile_created)
         VALUES (?, ?, ?, ?)');

        if ( $stmt->execute( [ $data[ 'email' ], $hash,$roleId, $data['profile_created']] ) ) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
    * Valida las credenciales en el inicio de sesión.
    */

    public function setProfileCreatedTrueByUserId(int $user_id){
        $sql = "UPDATE users SET profile_created = 1 WHERE id = ?
        ";

        $stmt = $this->db->prepare( $sql );
        return $stmt->execute( [ $user_id ] );
    }

    public function generateRegisterToken(int $userId, string $email, string $token, string $date){
        $sql = "INSERT INTO password_resets
                (user_id, email, token, expires_at)
                VALUES (?,?,?,?)
        ";

        $stmt = $this->db->prepare( $sql );
        return $stmt->execute( [ $userId, $email, $token, $date ] );

    }

    public function setTokenToExpired(int $token_id){
         $sql = "UPDATE password_resets SET expires_at = NOW() WHERE id = ?
        ";

        $stmt = $this->db->prepare( $sql );
        return $stmt->execute( [ $token_id ] );
    }

    public function login( string $email, string $password ) {
        // Traemos los datos de users y los datos de perfil de swimmers
        $sql = "SELECT u.*, p.first_name, p.profile_image 
            FROM users u
            LEFT JOIN profiles p ON u.id = p.user_id 
            WHERE u.email = ? AND u.deleted_at IS NULL 
            LIMIT 1";

        $stmt = $this->db->prepare( $sql );
        $stmt->execute( [ $email ] );
        $user = $stmt->fetch( PDO::FETCH_ASSOC );

        if ( $user && password_verify( $password, $user[ 'password' ] ) ) {
            return $user;
            // Retorna el array con email, role_id, first_name y profile_image
        }
        return false;
    }

    /**
    * Actualiza la contraseña de un usuario mediante su email.
    */

    public function updatePasswordByEmail(string $email, string $hashedPassword ) {
        $stmt = $this->db->prepare( 'UPDATE users SET password = ? WHERE email = ?' );
        return $stmt->execute( [ $hashedPassword, $email ] );
    }

    // --- SECCIÓN: RECUPERACIÓN DE CONTRASEÑA ( TOKENS ) ---

    /**
    * Guarda un token de recuperación, eliminando cualquier token previo del mismo email.
    */

    public function savePasswordToken(string $email, string $token, string $expires ) {
        try {
            // 1. Limpiamos registros de recuperación antiguos para este usuario
            $stmtDel = $this->db->prepare( 'DELETE FROM password_resets WHERE email = ?' );
            $stmtDel->execute( [ $email ] );

            // 2. Insertamos el nuevo token de seguridad
            $stmtIns = $this->db->prepare( 'INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)' );
            return $stmtIns->execute( [ $email, $token, $expires ] );

        } catch ( PDOException $e ) {
            error_log( 'Error en savePasswordToken: ' . $e->getMessage() );
            return false;
        }
    }

    public function getUsersCount(){
        $sql = "SELECT COUNT(DISTINCT u.id) as alumnos FROM users u";

        $stmt = $this->db->query($sql);

        $usersCount = $stmt->fetchColumn();

        return $usersCount;
    }

    /**
    * Valida si un token existe y no ha expirado.
    */

    public function validateToken(string $token ) {
        $stmt = $this->db->prepare( 'SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1' );
        $stmt->execute( [ $token ] );
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }

    /**
    * Elimina el token una vez que ya ha sido utilizado.
    */

    public function deleteToken(string $token ) {
        $stmt = $this->db->prepare( 'DELETE FROM password_resets WHERE token = ?' );
        return $stmt->execute( [ $token ] );
    }
}