<?php
// app/controllers/HomeController.php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Coach.php';
require_once __DIR__ . '/../models/Lesson.php';

class HomeController extends BaseController
{
    /**
     * Muestra el panel principal.
     * Ahora usa el motor de renderizado heredado de BaseController
     * para mantener la coherencia en todo el proyecto.
     */
    private $userModel;
    private $coachModel;
    private $activityLog;
    private $lessonModel;
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
        $this->activityLog = new SaveActivityLog();
        $this->lessonModel = new Lesson($pdo);
        $this->coachModel = new Coach($pdo);

    }

    public function adminHome()
    {
        $this->checkAuth(1);

        $data = [
            'title' => "Dashboard - Swimming School",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3
        ];

        $activeAlumns = $this->userModel->getCountByRole(3);
        $activeCoaches = $this->userModel->getCountByRole(2);
        $usersCount = $this->userModel->getUsersCount();

        $recentActivityLog = $this->activityLog->getLast5ActivityLogC();

        $data['active_alumns'] = $activeAlumns;
        $data['total_users'] = $usersCount;
        $data['active_coaches'] = $activeCoaches;
        $data['activity_log'] = $recentActivityLog;

        $this->render('administrator/home.view', $data);
    }

    public function coachHome()
    {
        $this->checkAuth(2);

        $lessons = $this->coachModel->getCoachHome($_SESSION['user_id']);

        $data = [
            'title'   => 'Home',
            'name'    => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 2,
            'lessons' => $lessons
        ];

        $this->render('coach/home.view', $data);
    }

    public function swimmerHome()
    {
        $this->checkAuth();

        $lessons = $this->lessonModel->getSwimmerHome($_SESSION['user_id']);

        $data = [
            'title'   => "Home",
            'name'    => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3,
            'lessons' => $lessons
        ];

        $this->render('swimmer/home.view', $data);
    }

}
