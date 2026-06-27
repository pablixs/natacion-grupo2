<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/Coach.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';
class RegistrationController extends BaseController
{
    private $userModel;
    private $profileModel;
    private $lessonModel;
    private $coachModel;
    private $activityLog;
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;

        $this->userModel = new User($pdo);
        $this->profileModel = new Profile($pdo);
        $this->coachModel = new Coach($pdo);
        $this->lessonModel = new Lesson($pdo);
        $this->activityLog = new SaveActivityLog($pdo);
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

        return $this->executeAdminRegistration($fields);
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

        return $this->executeAdminRegistration($fields);
    }

    private function executeAdminRegistration(array $f)
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

    public function completeRegistrationView()
    {
        // Si el parametro Token no está incluido se le asigna 
        // un string vacío para que se pueda validar correctamente
        $token = $_GET['token'] ?? '';

        // Chequeamos que el token exista en la db, si no existe
        // se lo reenvia al login

        if (!$this->validateToken($token)) {
            return header('Location: ?url=login');
        };

        $role_id = $this->userModel->getRoleByToken($token);
        $specialties = $this->lessonModel->getSpecialties();

        $data = [
            'title' => 'Completar registro',
            'token' => $_GET['token'],
            'role_id' => $role_id,
            'specialties' => $specialties
        ];

        $this->render('users/complete-register.view', $data);
    }

    public function completeRegistrationPost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return header('Location: ?url=login');
        }

        $token = $_GET['token'];

        $result = $this->userModel->getUserIdAndTokenIdByToken($token);

        $user_id = $result['user_id'];
        $token_id = $result['token_id'];
        // 1. Recolección y Sanitización ( Evitamos espacios vacíos y basura )
        $fields = [
            'first_name'    => trim($_POST['nombre'] ?? ''),
            'last_name'     => trim($_POST['apellido'] ?? ''),
            'password'       => $_POST['password'] ?? '',
            'passwordrepeat' => $_POST['passwordrepeat'] ?? '',
            'birth_date' => trim($_POST['birth_date'] ?? ''),
            'phone'          => trim($_POST['telefono'] ?? ''),
            'specialty' => $_POST['especialidad'] ?? null,
            'profile_image'  => 'default-profile.png', //valor por defecto
            'user_id' => $user_id,
            'token_id' => $token_id

        ];


        // 2. Validaciones Críticas ( Uso de 'Early Returns' para evitar anidación de IFs )
        if ($this->hasEmptyFields($fields)) {
            return $this->json('warning', implode(',', $fields));
        }

        // Validando minimo y maximo de numero de telefono
        if (strlen($fields['phone']) < 6 || strlen($fields['phone']) > 15) {
            return $this->json('warning', 'El número de teléfono debe tener de 6 a 15 números');
        }

        // Validando contraseña repetida
        if ($fields['password'] !== $fields['passwordrepeat']) {
            return $this->json('warning', 'Las contraseñas no coinciden');
        }


        if (strlen($fields['password']) < 6) {
            return $this->json('warning', 'La contraseña es muy corta (mín. 6 caracteres).');
        }


        // --- GESTIÓN DE IMAGEN DE PERFIL ---
        $tempFile = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/img/uploads/profiles/swimmers/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($extension, $allowed)) {

                // 1. Tomamos la inicial del nombre en minúscula
                $initial = strtolower(substr($fields['first_name'], 0, 1));

                // 2. Limpiamos el apellido ( quitamos espacios y pasamos a minúscula )
                $lastName = strtolower(str_replace(' ', '', $fields['last_name']));

                // 3. Generamos un número aleatorio de 4 dígitos para evitar colisiones ( Juan Perez vs Jorge Perez )
                $randomNumber = rand(1000, 9999);

                // Resultado ej: jperez_4521.jpg
                $newFileName = 'swimmer_' . $initial . $lastName . '_' . $randomNumber . '.' . $extension;
                $absolutePath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $absolutePath)) {
                    $fields['profile_image'] = $newFileName;
                    $tempFile = $absolutePath;
                }
            }
        }

        return $this->executeProfileCompletion($fields, $tempFile);
    }

    private function executeProfileCompletion($f, $tempFile = null)
    {

        try {
            $this->pdo->beginTransaction();

            $this->profileModel->create($f);

            $hashedPassword = password_hash($f['password'], PASSWORD_BCRYPT);
            $user_id = $f['user_id'];
            $token_id = $f['token_id'];

            $this->coachModel->updatePasswordWhenSaveProfile($hashedPassword, $user_id);
            $this->userModel->setProfileCreatedTrueByUserId($user_id);
            $this->userModel->setTokenToExpired($token_id);

            $this->activityLog->newLog('profile_completed', ['name' => $f['first_name'] . " " . $f['last_name']]);


            $this->pdo->commit();

            $role_id = $this->userModel->getRoleByToken($_GET['token'] ?? '');
            if ($role_id == 2 || $this->userModel->getRoleByToken($token_id) == 2) {
                $specialties = $_POST['specialties'] ?? [];
                if (!empty($specialties)) {
                    $this->lessonModel->saveCoachSpecialties($user_id, $specialties);
                }
            }

            // 1. Obtenemos la URL base del .env ( ej: http://localhost/gestion-natacion )
            $baseUrl = rtrim(Env::get('APP_URL'), '/');

            // 2. Si por algún error el .env está vacío, fallamos con una base segura
            if (empty($baseUrl)) {
                $baseUrl = 'http://localhost/gestion-natacion';
            }

            // 3. Construimos la URL final
            $loginUrl = $baseUrl . '/?url=login';

            return $this->json('success', '¡Registro completado!', $loginUrl);
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            // Si algo falló en SQL, borramos la foto para no dejar basura
            if ($tempFile && file_exists($tempFile)) {
                unlink($tempFile);
            };
            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }
    }

    public function validateToken(string $token)
    {
        return $this->coachModel->validateToken($token);
    }


    private function hasEmptyFields($f)
    {
       return empty($f['first_name']) || empty($f['last_name']) || empty($f['password']) || empty($f['phone']) || empty($f['birth_date']);
    }
}
