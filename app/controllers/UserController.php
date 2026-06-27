<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../services/MailService.php';

class UserController extends BaseController
{
    private $userModel;
    private $lessonModel;
    private $profileModel;
    private $mailService;
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
        $this->lessonModel = new Lesson($pdo);
        $this->mailService = new MailService();
    }

    // --- SECCIÓN: VISTAS Y LISTADOS ---

    /**
     * Lista todos los nadadores registrados.
     * Ideal para mostrar cómo se consumen datos con JOINs desde el modelo.
     */

    public function coachesView()
    {
        $this->checkAuth(1);

        $data = [
            'title'           => 'Gestión de Profesores',
            'user'            => $_SESSION['email'] ?? 'Guest',
            'name'            => $_SESSION['first_name'] ?? 'Guest',
            'role_id'         => $_SESSION['role_id'] ?? 1,
            'users_data'      => $this->profileModel->getAllByRoleWithStatus(2),
            'page_title'      => 'Gestión de Profesores',
            'register_url'    => '?url=register-coach',
            'register_label'  => 'Dar de alta profesor',
            'empty_icon'      => 'fa-user-tie',
            'empty_message'   => 'No hay profesores registrados'
        ];

        $this->render('administrator/coach/coaches.view', $data);
    }

    public function swimmersView()
    {
        $this->checkAuth(1);

        $data = [
            'title'           => 'Gestión de Alumnos',
            'user'            => $_SESSION['email'] ?? 'Guest',
            'name'            => $_SESSION['first_name'] ?? 'Guest',
            'role_id'         => $_SESSION['role_id'] ?? 1,
            'users_data'      => $this->profileModel->getAllByRoleWithStatus(3),
            'page_title'      => 'Gestión de Alumnos',
            'register_url'    => '?url=register-swimmer',
            'register_label'  => 'Dar de alta alumno',
            'empty_icon'      => 'fa-person-swimming',
            'empty_message'   => 'No hay alumnos registrados'
        ];

        $this->render('administrator/swimmer/swimmers.view', $data);
    }

    public function getUsersAndProfiles()
    {
        $this->checkAuth(1);

        $users = $this->userModel->getUsersAndProfiles();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'users' => $users]);
        exit;
    }

    public function editUserView()
    {
        $this->checkAuth(1);

        $userId = intval($_GET['id'] ?? 0);

        if ($userId <= 0) {
            header('Location: ?url=home');
            exit;
        }

        $userData = $this->userModel->getUserWithProfileById($userId);

        if (!$userData) {
            header('Location: ?url=home');
            exit;
        }
        
        $specialties = $this->lessonModel->getSpecialties();
        $coachSpecialtyIds = $userData['role_id'] == 2
            ? $this->lessonModel->getCoachSpecialtyIds($userId)
            : [];


        $data = [
            'title'    => 'Editar Usuario',
            'user'     => $_SESSION['email'] ?? 'Guest',
            'name'     => $_SESSION['first_name'] ?? 'Guest',
            'role_id'  => $_SESSION['role_id'] ?? 1,
            'userData' => $userData,
            'back_url' => $userData['role_id'] == 2 ? '?url=coaches' : '?url=swimmers',
            'specialties'       => $specialties,
            'coachSpecialtyIds' => $coachSpecialtyIds
        ];

        $this->render('administrator/edit-user.view', $data);
    }


    public function updateUserPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return http_response_code(405);
        }

        $userId = intval($_POST['user_id'] ?? 0);

        if ($userId <= 0) {
            return $this->json('error', 'ID de usuario inválido.');
        }

        $fields = [
            'email'      => trim($_POST['email'] ?? ''),
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name'  => trim($_POST['last_name'] ?? ''),
            'phone'      => trim($_POST['phone'] ?? ''),
            'birth_date' => trim($_POST['birth_date'] ?? ''),
            'specialty'  => trim($_POST['specialty'] ?? '') ?: null,
            'role_id' =>    trim($_POST['role_id'] ?? 3),
        ];

        if (empty($fields['email']) || empty($fields['first_name']) || empty($fields['last_name']) || empty($fields['phone'])) {
            return $this->json('warning', 'Faltan datos obligatorios.');
        }

        if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json('error', 'El email no es válido.');
        }

        if (!in_array($fields['role_id'], [1, 2, 3])) {
            return $this->json('error', 'Rol no válido.');
        }

        $existing = $this->userModel->findByEmail($fields['email']);
        if ($existing && $existing['id'] != $userId) {
            return $this->json('error', 'Ese email ya está registrado por otro usuario.');
        }

        try {
            $updated = $this->userModel->updateUserWithProfile($userId, $fields);

            if (!$updated) {
                return $this->json('error', 'No se pudo actualizar el usuario.');
            }

            if ($fields['role_id'] == 2 && isset($_POST['specialties'])) {
                $this->lessonModel->saveCoachSpecialties($userId, $_POST['specialties']);
            }

            $backUrl = $fields['role_id'] == 2 ? '?url=coaches' : '?url=swimmers';

            return $this->json('success', '¡Usuario actualizado correctamente!', $backUrl);
        } catch (Exception $e) {
            return $this->json('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function softDeleteUserPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return http_response_code(405);
        }

        $userId = intval($_POST['user_id'] ?? 0);

        if ($userId <= 0) {
            return $this->json('error', 'ID de usuario inválido.');
        }

        if ($userId == $_SESSION['user_id']) {
            return $this->json('error', 'No podés desactivar tu propia cuenta.');
        }

        try {
            $deleted = $this->userModel->softDeleteUser($userId);

            if (!$deleted) {
                return $this->json('error', 'No se pudo desactivar el usuario.');
            }

            return $this->json('success', 'Usuario desactivado correctamente.');
        } catch (Exception $e) {
            return $this->json('error', 'Error al desactivar: ' . $e->getMessage());
        }
    }

    public function enableUserPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return http_response_code(405);
        }

        $userId = intval($_POST['user_id'] ?? 0);

        if ($userId <= 0) {
            return $this->json('error', 'ID de usuario inválido.');
        }

        if ($userId == $_SESSION['user_id']) {
            return $this->json('error', 'No podés desactivar tu propia cuenta.');
        }

        try {
            $deleted = $this->userModel->activateUser($userId);

            if (!$deleted) {
                return $this->json('error', 'No se pudo activar el usuario.');
            }

            return $this->json('success', 'Usuario activado correctamente.');
        } catch (Exception $e) {
            return $this->json('error', 'Error al activar: ' . $e->getMessage());
        }
    }


    public function sendPasswordResetPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return http_response_code(405);
        }

        $userId = intval($_POST['user_id'] ?? 0);

        if ($userId <= 0) {
            return $this->json('error', 'ID de usuario inválido.');
        }

        $user = $this->userModel->getUserWithProfileById($userId);

        if (!$user) {
            return $this->json('error', 'Usuario no encontrado.');
        }

        try {
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $this->userModel->savePasswordToken($userId, $user['email'], $token, $expires);
            $this->mailService->sendEmailResetPassword($user['email'], $token);

            return $this->json('success', 'Se envió un enlace de cambio de contraseña a ' . $user['email']);
        } catch (Exception $e) {
            return $this->json('error', 'No se pudo enviar el email: ' . $e->getMessage());
        }
    }
}
