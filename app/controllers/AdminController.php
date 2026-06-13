<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';


class AdminController extends BaseController
{
    private $pdo;
    private $userModel;
    private $profileModel;
    private $lessonModel;
    private $activityLog;

    public function __construct()
    {
        /** * Usamos 'global $pdo' porque la conexión se crea en otro archivo ( ej. index o database ).
         * Sin esto, el controlador no tendría acceso al objeto de conexión PDO para pasárselo a los modelos.
         * Es una forma de 'inyectar' la base de datos sin volver a conectarse en cada clase.
         */
        global $pdo;
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
        $this->profileModel = new Profile($pdo);
        $this->lessonModel = new Lesson($pdo);
        $this->activityLog = new SaveActivityLog($pdo);
    }

    public function newLessonView(){
         $this->checkAuth(1);


         $coaches = $this->profileModel->getAllDataByRole(2);
         $specialties = $this->lessonModel->getSpecialties();

        $data = [
            'title' => "Manage Users Dashboard",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 1,
            'coaches' => $coaches,
            'specialties' => $specialties
        ];



        $this->render('administrator/new-lesson.view', $data);
    }



    public function manageLessonsView(){
         $this->checkAuth(1);


         $lessons = $this->lessonModel->getLessons();

        $data = [
            'title' => "Manage Users Dashboard",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 1,
            'lessons' => $lessons
        ];



        $this->render('administrator/manage-lessons.view', $data);
    }

    public function newLessonPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return http_response_code(405);
        }

        // // 1. Recolección y Sanitización ( Evitamos espacios vacíos y basura )



        $fields = [
            'coach_id' => trim($_POST['coach_id'] ?? ''),
            'level' => trim($_POST['level'] ?? ''),
            'specialties' =>  $_POST['specialties'],
            'first_day_of_week' => trim($_POST['first_day_of_week'] ?? ''),
            'second_day_of_week' => trim($_POST['second_day_of_week'] ?? null),
            'start_time' => trim($_POST['start_time'] ?? ''),
            'end_time' => trim($_POST['end_time'] ?? ''),
            'capacity' => trim($_POST['capacity'] ?? ''),
            'active' => isset($_POST['active']) ? 1 : 0

        ];

        $days = [
            "Lunes",
            "Martes",
            "Miércoles",
            "Jueves",
            "Viernes",
            "Sábado"
        ];

        $levels = [
            "Principiante",
            "Intermedio",
            "Avanzado"
        ];


        if (empty($fields['coach_id']) || empty($fields['level']) || empty($fields['specialties']) || empty($fields['first_day_of_week']) || empty($fields['start_time']) || empty($fields['end_time']) || empty($fields['capacity'])) {
            return $this->json('warning', 'Faltan datos obligatorios.');
        }

        if (!in_array($fields['level'], $levels)) {
            return $this->json('error', 'Nivel no válido.');
        }

        if (!in_array($fields['first_day_of_week'], $days)) {
            return $this->json('error', 'Primer día de la semana no válido.');
        }

        if (!is_null($fields['second_day_of_week']) && !in_array($fields['second_day_of_week'], $days)) {
            return $this->json('error', 'Segundo día de la semana no válido.');
        }

        try {
            $created = $this->lessonModel->createClass($fields);

            if (!$created) {
                return $this->json('error', 'No se pudo crear la clase.');
            }

            // $this->activityLog->newLog('class_created', ['coach_id' => $fields['coach_id'], 'level' => $fields['level']]);
            return $this->json('success', '¡Clase creada! - debug: ' . json_encode($fields) . ' - created: ' . json_encode($created));
        } catch (Exception $e) {
            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }

        return $this->json('success', '¡Clase creada! - debug: ' . json_encode($fields) . ' - created: ');
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

    public function registerSwimmerView()
    {
        // Solo permitimos pasar al role id 1 (admin)
        $this->checkAuth(1);

        $data = [
            'title' => "Dar de alta un alumno - Swimming School",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3
        ];

        $this->render('administrator/swimmer/register-swimmer.view', $data);
    }

    // Refactorizar más adelante un solo registerPost y pasar el 
    // role id por parametro para evitar repetir
    public function registerSwimmerPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return header('Location: ?url=home');
        }

        // 1. Recolección y Sanitización ( Evitamos espacios vacíos y basura )
        $fields = [
            'email'          => trim($_POST['email'] ?? ''),
            'role_id'         => 3 /* Rol: swimmer */,
            'need_change_password' => 1,
        ];


        // 2. Validaciones Críticas ( Uso de 'Early Returns' para evitar anidación de IFs )
        if (empty($fields['email'])) {
            return $this->json('warning', 'Faltan datos obligatorios.');
        }

        if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json('error', 'El email ingresado no es válido.');
        }

        return $this->executeRegistration($fields);
    }

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


    public function registerCoachView()
    {
        // Solo permitimos pasar al role id 1 (admin)
        $this->checkAuth(1);



        $data = [
            'title' => "Dar de alta un profesor - Swimming School",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3,
        ];

        $this->render('administrator/coach/register-coach.view', $data);
    }

    public function registerCoachPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return header('Location: ?url=home');
        }

        // 1. Recolección y Sanitización ( Evitamos espacios vacíos y basura )
        $fields = [
            'email'          => trim($_POST['email'] ?? ''),
            'role_id'         => 2 /* Rol: profesor */,
            'need_change_password' => 1,
        ];


        // 2. Validaciones Críticas ( Uso de 'Early Returns' para evitar anidación de IFs )
        if (empty($fields['email'])) {
            return $this->json('warning', 'Faltan datos obligatorios.');
        }

        if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json('error', 'El email ingresado no es válido.');
        }

        return $this->executeRegistration($fields);
    }

    private function executeRegistration(array $f)
    {
        $this->checkAuth(1);

        try {
            if ($this->userModel->findByEmail($f['email'])) {
                return $this->json('user_exists', '.', Env::get('APP_URL') . '/?url=coaches');
            }

            $this->pdo->beginTransaction();

            $email = $f['email'];
            $role_id = $f['role_id'];

            // Tabla: users
            $userId = $this->userModel->createUser([
                'email'    => $email,
                'password' => "adminpassword",
                'role_id'  => $role_id,
                'profile_created' => 0
            ]);

            if (!$userId) throw new Exception('Error al crear credenciales.');


            $token = bin2hex(random_bytes(16));
            $today_date = date("Y-m-d");
            $expires_at_DateTime = new DateTime($today_date);
            $expires_at = $expires_at_DateTime->modify("+3 days")->format("Y-m-d");

            $this->userModel->generateRegisterToken(
                $userId,
                $f['email'],
                $token,
                $expires_at
            );

            match ($role_id) {
                2 => $this->activityLog->newLog('coach_registered', ['email' => $f['email']]),
                3 => $this->activityLog->newLog('swimmer_registered', ['email' => $f['email']])
            };

            $this->sendCompleteRegister($email, $token, $role_id);

            $this->pdo->commit();

            // 1. Obtenemos la URL base del .env ( ej: http://localhost/gestion-natacion )
            $baseUrl = rtrim(Env::get('APP_URL'), '/');

            // 2. Si por algún error el .env está vacío, fallamos con una base segura
            if (empty($baseUrl)) {
                $baseUrl = 'http://localhost/natacion-grupo2/';
            }

            // 3. Construimos la URL final
            $coachesUrl = $baseUrl . '/?url=home';


            return $this->json('success', '¡Registro completado!');
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();

            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }
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

    /**
     * Lógica de inscripción con Transacción SQL.
     * Enseñamos que si algo falla en el medio, no debe quedar basura en la DB.
     */



    private function hasEmptyFields(array $f)
    {
        return empty($f['first_name']) || empty($f['last_name']) || empty($f['email']) || empty($f['password'] || empty($f['phone'] || empty($f['specialty'])));
    }

    // public function testSendEmail(string $email)
    // {
    //     try {

    //         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //             return $this->index();
    //         }

    //         require_once __DIR__ . '/../services/MailService.php';
    //         $mailService = new MailService();

    //         $enviado = $mailService->sendEmailCompleteProfile($email, $token = 'dou');

    //         return $this->json('success', 'Piolita', $enviado);
    //     } catch (Exception $e) {
    //         return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
    //     }
    // }

    private function sendCompleteRegister(string $email, string $token, int $role_id)
    {
        require_once __DIR__ . '/../services/MailService.php';
        $mailService = new MailService();
        $enviado = false;

        switch ($role_id) {
            case '2':
                $enviado = $mailService->sendEmailCompleteProfileCoach($email, $token);
                break;
            case '3':
                $enviado = $mailService->sendEmailCompleteProfileSwimmer($email, $token);
                break;
            default:
                break;
        }

        return $enviado;
    }
}
