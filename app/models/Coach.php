<?php

class Coach {
    private $db;

    public function __construct( $pdo ) {
        $this->db = $pdo;
    }

    // --- SECCIÓN: BÚSQUEDA E IDENTIFICACIÓN ---

    /**
    * Busca un usuario por email.
    * @return array|bool Retorna los datos del usuario o false si no existe.
    */

    
    public function validateToken(string $token ) {
        $stmt = $this->db->prepare( 'SELECT 1 FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1' );
        $stmt->execute([$token]);
        return $stmt->fetch( PDO::FETCH_ASSOC );
    }


    public function updatePasswordWhenSaveProfile(string $hashedNewPassword, int $user_id){
        $stmt = $this->db->prepare( 'UPDATE users SET password = ? WHERE id = ?' );
        return $stmt->execute( [ $hashedNewPassword, $user_id ] );
    }
    

}