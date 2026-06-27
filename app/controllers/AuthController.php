<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Coach.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';

class AuthController extends BaseController
{


    private $profileModel;
    private $activityLog;
    private $userModel;
    private $coachModel;
    private $pdo;
    
    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        
        $this->userModel = new User($pdo);
        $this->coachModel = new Coach($pdo);
        $this->profileModel = new Profile($pdo);
        $this->activityLog = new SaveActivityLog($pdo);
    }


    public function showLogin()
    {
        $this->render('users/login.view');
    }
    public function showRegister()
    {
        $this->render('users/register.view');
    }

    public function createAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->showRegister();
        }

        $fields = [
            'first_name'     => trim($_POST['nombre'] ?? ''),
            'last_name'      => trim($_POST['apellido'] ?? ''),
            'email'          => trim($_POST['email'] ?? ''),
            'password'       => $_POST['password'] ?? '',
            'passwordrepeat' => $_POST['passwordrepeat'] ?? '',
            'birth_date'     => trim($_POST['birth_date'] ?? ''),
            'phone'          => trim($_POST['telefono'] ?? ''),
            'profile_image'  => 'default-profile.png'
        ];

        if ($this->hasEmptyFields($fields)) {
            return $this->json('warning', 'Faltan datos obligatorios.' . $this->hasEmptyFields($fields));
        }

        if (strlen($fields['phone']) < 6 || strlen($fields['phone']) > 15) {
            return $this->json('warning', 'El número de teléfono debe tener de 6 a 15 números.');
        }

        if ($fields['password'] !== $fields['passwordrepeat']) {
            return $this->json('warning', 'Las contraseñas no coinciden.');
        }

        if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json('error', 'El email ingresado no es válido.');
        }

        if (strlen($fields['password']) < 6) {
            return $this->json('warning', 'La contraseña es muy corta (mín. 6 caracteres).');
        }

        // --- Imagen de perfil ---
        $tempFile = null;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/img/uploads/profiles/swimmers/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($extension, $allowed)) {
                $initial    = strtolower(substr($fields['first_name'], 0, 1));
                $lastName   = strtolower(str_replace(' ', '', $fields['last_name']));
                $randomNumber = rand(1000, 9999);
                $newFileName  = 'swimmer_' . $initial . $lastName . '_' . $randomNumber . '.' . $extension;
                $absolutePath = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $absolutePath)) {
                    $fields['profile_image'] = $newFileName;
                    $tempFile = $absolutePath;
                }
            }
        }

        try {
            if ($this->userModel->findByEmail($fields['email'])) {
                if ($tempFile && file_exists($tempFile)) unlink($tempFile);
                $loginUrl = rtrim(Env::get('APP_URL'), '/') . '/?url=login';
                return $this->json('user_exists', 'Ya tenés una cuenta registrada.', $loginUrl);
            }

            $this->pdo->beginTransaction();

            $userId = $this->userModel->createUser([
                'email'           => $fields['email'],
                'password'        => $fields['password'],
                'role_id'         => 3,
                'profile_created' => 1
            ]);

            if (!$userId) throw new Exception('Error al crear credenciales.');

            $fields['user_id'] = $userId;
            $this->profileModel->create($fields);

            $this->pdo->commit();

            $loginUrl = rtrim(Env::get('APP_URL'), '/') . '/?url=login';

            $this->activityLog->newLog('swimmer_self_registered', ['name' => $fields['first_name'] . ' ' . $fields['last_name']]);
            return $this->json('success', '¡Registro completado!', $loginUrl);
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            if ($tempFile && file_exists($tempFile)) unlink($tempFile);
            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }
    }

    


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

    public function forgotPassword()
    {
        $this->render('users/forgot-password.view', ['title' => 'Recuperar Contraseña']);
    }

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

            $user_id = $user['id'];
            $saved = $this->userModel->savePasswordToken($user_id, $email, $token, $expires);

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

        if (!$this->validateToken($token)) {
            return header('Location: ?url=login');
        };

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

        try {
            if ($resetRequest) {
                $email = $resetRequest['email'];
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                try {
                    $this->pdo->beginTransaction();

                    $this->userModel->updatePasswordByEmail($email, $hashedPassword);
                    $this->userModel->setTokenToExpiredPassword($token);

                    $this->pdo->commit();
                    return $this->json('success', '¡Contraseña actualizada con éxito!', Env::get('APP_URL') . '?url=login');
                } catch (Exception $e) {
                    if ($this->pdo->inTransaction()) $this->pdo->rollBack();
                    return $this->json('error', 'No se pudo actualizar la contraseña.');
                }
            }

            return $this->json('error', 'El enlace es inválido o ha expirado.');
            //code...
        } catch (\Throwable $th) {
            return $this->json('error', 'error trycatch ' . $th);
        }
    }

    public function validateToken(string $token)
    {
        return $this->coachModel->validateToken($token);
    }

    private function hasEmptyFields($f)
    {
        return empty($f['first_name']) || empty($f['last_name']) || empty($f['email']) || empty($f['password']) || empty($f['phone']) || empty($f['birth_date']);
    }
}
