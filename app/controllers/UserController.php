<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/Coach.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';

class UserController extends BaseController
{
    private $userModel;
    private $profileModel;
    private $coachModel;
    private $activityLog;
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
        $this->coachModel = new Coach($pdo);
        $this->activityLog = new SaveActivityLog($pdo);
    }

    // --- SECCIÓN: VISTAS Y LISTADOS ---

    /**
     * Lista todos los nadadores registrados.
     * Ideal para mostrar cómo se consumen datos con JOINs desde el modelo.
     */

    public function index()
    {
        $this->checkAuth();
        // Seguridad: si no hay sesión, al login.

        $swimmers = $this->userModel->getCountByRole(3);
        $this->render('users/index', ['swimmers' => $swimmers]);
    }

    public function showLogin()
    {
        $this->render('users/login.view');
    }

    public function forgotPassword()
    {
        $this->render('users/forgot-password.view', ['title' => 'Recuperar Contraseña']);
    }

    /**
     * Lógica de inscripción con Transacción SQL.
     * Enseñamos que si algo falla en el medio, no debe quedar basura en la DB.
     */

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

        $data = [
            'title' => 'Completar registro',
            'token' => $_GET['token'],
            'role_id' => $role_id
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

        return $this->executeRegistration($fields, $tempFile);
    }


      private function executeRegistration($f, $tempFile = null){

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


    /**
     * Procesa la autenticación de usuarios.
     */

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json('error', 'Acceso no permitido.');
        }

        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';

        $user = $this->userModel->login($email, $pass);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['email']   = $user['email'];
            // Datos para el saludo y la foto que pide el nuevo layout
            $_SESSION['first_name']    = $user['first_name'];

            $_SESSION['profile_image'] = $user['profile_image'];

            return $this->json('success', '¡Bienvenido ' . $user['first_name'] . '!', Env::get('APP_URL') . '/?url=home');
        }

        return $this->json('error', 'Credenciales incorrectas.');
    }




    // --- SECCIÓN: RECUPERACIÓN DE CONTRASEÑA ---

    public function sendReset()
    {
        $email = $_POST['email'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json('error', 'Email inválido.');
        }

        $user = $this->userModel->findByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $this->userModel->savePasswordToken($email, $token, $expires);

            require_once __DIR__ . '/../services/MailService.php';
            $mailService = new MailService();

            $enviado = $mailService->sendEmailResetPassword($email, $token);

            if (!$enviado) {
                return $this->json('error', 'El servidor de correo falló.');
            }
        }

        return $this->json('success', 'Si el correo existe, recibirás un enlace de recuperación.', Env::get('APP_URL') . '/?url=login');
    }

    public function showResetForm()
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            die('Error: El token de recuperación ha expirado o es inválido.');
        }

        $this->render('users/reset-password.view', [
            'title' => 'Restablecer Contraseña',
            'token' => $token
        ]);
    }

    public function updatePassword()
    {
        $token    = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($token) || strlen($password) < 6) {
            return $this->json('warning', 'La contraseña debe tener al menos 6 caracteres.');
        }

        $resetRequest = $this->userModel->validateToken($token);

        if ($resetRequest) {
            $email = $resetRequest['email'];
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            try {
                $this->pdo->beginTransaction();

                $this->userModel->updatePasswordByEmail($email, $hashedPassword);
                $this->userModel->deleteToken($token);

                $this->pdo->commit();
                return $this->json('success', '¡Contraseña actualizada con éxito!', Env::get('APP_URL') . '?url=login');
            } catch (Exception $e) {
                if ($this->pdo->inTransaction()) $this->pdo->rollBack();
                return $this->json('error', 'No se pudo actualizar la contraseña.');
            }
        }

        return $this->json('error', 'El enlace es inválido o ha expirado.');
    }

    public function validateToken(string $token)
    {
        return $this->coachModel->validateToken($token);
    }
    

    private function hasEmptyFields($f)
    {
        return empty($f['first_name']) || empty($f['last_name']) || empty($f['password'] || empty($f['phone'] || empty($f['birth_date'])));
    }
}
