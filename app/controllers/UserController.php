<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';

class UserController extends BaseController
{
    private $userModel;
    private $profileModel;

    private $pdo;

    public function __construct()
    {
        /** * Usamos 'global $pdo' porque la conexión se crea en otro archivo ( ej. index o database ).
         * Sin esto, el controlador no tendría acceso al objeto de conexión PDO para pasárselo a los modelos.
         * Es una forma de 'inyectar' la base de datos sin volver a conectarse en cada clase.
         */
        global $pdo;
        $this->pdo = $pdo;

        // Inicializamos los modelos pasándoles la conexión única
        $this->userModel = new User($pdo);
        $this->profileModel = new Profile($pdo);
    }

    // --- SECCIÓN: VISTAS Y LISTADOS ---

    /**
     * Lista todos los nadadores registrados.
     * Ideal para mostrar cómo se consumen datos con JOINs desde el modelo.
     */

    public function coachesView()
    {
        // Solo permitimos pasar al role id 1 (admin)
        $this->checkAuth(1);
        $coaches_data = $this->profileModel->getAllDataByRole(2);

        $data = [
            'title' => "Profesores - Swimming School",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3,
            'coaches_data' => $coaches_data

        ];

        $data['coachs_data'] = $this->userModel->getCountByRole(2);

        $this->render('administrator/coach/coaches.view', $data);
    }

    public function swimmersView()
    {
        // Solo permitimos pasar al role id 1 (admin)
        $this->checkAuth(1);


        $swimmers_data = $this->profileModel->getAllDataByRole(3);

        $data = [
            'title' => "Manage Users Dashboard",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3,
            'swimmers_data' => $swimmers_data
        ];

        $this->render('administrator/swimmer/swimmers.view', $data);
    }

    public function getUsersAndProfiles()
    {
        $this->checkAuth(1);

        try {

            $data = $this->userModel->getUsersAndProfiles();

            $this->json('success', $data);
        } catch (Exception $e) {

            $this->json('error', $e->getMessage());
        }
    }

}
