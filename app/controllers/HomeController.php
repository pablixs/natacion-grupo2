<?php
// app/controllers/HomeController.php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Swimmer.php';
class HomeController extends BaseController {
    /**
     * Muestra el panel principal.
     * Ahora usa el motor de renderizado heredado de BaseController
     * para mantener la coherencia en todo el proyecto.
     */
    private $userModel;
    private $swimmerModel;
    private $pdo;

    public function __construct() {
        /** * Usamos 'global $pdo' porque la conexión se crea en otro archivo ( ej. index o database ).
        * Sin esto, el controlador no tendría acceso al objeto de conexión PDO para pasárselo a los modelos.
        * Es una forma de 'inyectar' la base de datos sin volver a conectarse en cada clase.
        */
        global $pdo;
        $this->pdo = $pdo;

        // Inicializamos los modelos pasándoles la conexión única
        $this->userModel = new User( $pdo );
        $this->swimmerModel = new Swimmer( $pdo );
    }

    public function index() {
        // Verificamos si el usuario está logueado antes de mostrar el panel
        $this->checkAuth();

        $data = [
            'title' => "Dashboard - Swimming School",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3
        ];

        // El método render busca automáticamente en /views/ y permite pasar dato
        switch($data['role_id']){
            // Caso de rol administrador
            case 1: 
                $activeAlumns = $this->swimmerModel->getSwimmersCount();
                $usersCount = $this->userModel->getUsersCount();

                $data['active_alumns'] = $activeAlumns;
                $data['total_users'] = $usersCount;

                $this->render('administrator/home.view', $data);
                break;
            case 2: 
            // Caso de rol profesor/coach
                break;
            case 3: 
            // Caso de rol swimmer
                $this->render('home.view', $data);
                break;
        }
        
    }
}