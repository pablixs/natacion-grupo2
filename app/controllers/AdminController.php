<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Admin.php';
// require_once __DIR__ . '/../models/User.php';
// require_once __DIR__ . '/../models/Swimmer.php';

class AdminController extends BaseController
{
    private $pdo;
    private $adminModel;

    public function __construct()
    {
        /** * Usamos 'global $pdo' porque la conexión se crea en otro archivo ( ej. index o database ).
         * Sin esto, el controlador no tendría acceso al objeto de conexión PDO para pasárselo a los modelos.
         * Es una forma de 'inyectar' la base de datos sin volver a conectarse en cada clase.
         */
        global $pdo;
        $this->pdo = $pdo;
        $this->adminModel = new Admin($pdo);
    }

    public function index()
    {
        $this->checkAuth();

        $data = [
            'title' => "Dashboard - Swimming School",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3
        ];

        $data['coachsData'] = $this->adminModel->getAllCoachs();

        $this->render('administrator/coaches.view', $data);
    }
}
