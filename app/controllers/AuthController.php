<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Coach.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';
class AuthController extends BaseController
{
    private $userModel;
    private $coachModel;
    private $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;

        $this->userModel = new User($pdo);
        $this->coachModel = new Coach($pdo);
    }


    public function showLogin()
    {
        $this->render('users/login.view');
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

        return $this->json('success', 'Si el correo existe, recibirás un enlace de recuperación. saved: ' . $saved, Env::get('APP_URL') . '/?url=login');
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
}
