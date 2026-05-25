<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';

class AdminController extends BaseController
{
    private $pdo;
    private User $userModel;
    private $profileModel;

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
    }

    public function index()
    {
        // Solo permitimos pasar al role id 1 (admin)
        $this->checkAuth(1);

        $data = [
            'title' => "Profesores - Swimming School",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3
        ];

        $data['coachs_data'] = $this->userModel->getCountByRole(2);

        $this->render('administrator/coaches.view', $data);
    }


    public function registerCoachView()
    {
        // Solo permitimos pasar al role id 1 (admin)
        $this->checkAuth(1);

        $data = [
            'title' => "Dar de alta un profesor - Swimming School",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3
        ];

        $this->render('administrator/register-coach.view', $data);
    }

    public function registerCoachPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->index();
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

    /**
     * Lógica de inscripción con Transacción SQL.
     * Enseñamos que si algo falla en el medio, no debe quedar basura en la DB.
     */

    private function executeRegistration(array $f)
    {
        $this->checkAuth(1);

        try {
            if ($this->userModel->findByEmail($f['email'])) {
                return $this->json('user_exists', '.', Env::get('APP_URL') . '/?url=coaches');
            }

            $this->pdo->beginTransaction();

            $email = $f['email'];

            // Tabla: users
            $userId = $this->userModel->createUser([
                'email'    => $email,
                'password' => "adminpassword",
                'role_id'  => $f['role_id'],
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

            $this->sendCompleteRegister($email, $token);

            $this->pdo->commit();

            // 1. Obtenemos la URL base del .env ( ej: http://localhost/gestion-natacion )
            $baseUrl = rtrim(Env::get('APP_URL'), '/');

            // 2. Si por algún error el .env está vacío, fallamos con una base segura
            if (empty($baseUrl)) {
                $baseUrl = 'http://localhost/natacion-grupo2/';
            }

            // 3. Construimos la URL final
            $coachesUrl = $baseUrl . '/?url=coaches';



            return $this->json('success', implode(",", $f) . " userid: " . $userId, $coachesUrl);
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();

            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }
    }

    private function hasEmptyFields(array $f)
    {
        return empty($f['first_name']) || empty($f['last_name']) || empty($f['email']) || empty($f['password'] || empty($f['phone'] || empty($f['specialty'])));
    }

    public function testSendEmail(string $email)
    {
        try {

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return $this->index();
            }
            
            require_once __DIR__ . '/../services/MailService.php';
            $mailService = new MailService();

            $enviado = $mailService->sendEmailCompleteProfile($email, $token = 'dou');

            return $this->json('success', 'Piolita', $enviado);
        } catch (Exception $e) {
            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }
    }

    private function sendCompleteRegister(string $email, string $token)
    {
        require_once __DIR__ . '/../services/MailService.php';
        $mailService = new MailService();

        return $enviado = $mailService->sendEmailCompleteProfile($email, $token);
    }
}
