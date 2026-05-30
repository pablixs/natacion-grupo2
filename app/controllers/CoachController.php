<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../models/Coach.php';

class CoachController extends BaseController
{
    private $pdo;
    private User $userModel;
    private $profileModel;
    private $coachModel;

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
        $this->coachModel = new Coach($pdo);
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

        $data = [
            'title' => 'Completar registro - Coach',
            'token' => $_GET['token']
        ];

        $this->render('coaches/complete-register.view', $data);
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
            'specialty' => $_POST['especialidad'],
            'profile_image'  => 'default-profile.png', //valor por defecto
            'user_id' => $user_id,
            'token_id' => $token_id

        ];


        // 2. Validaciones Críticas ( Uso de 'Early Returns' para evitar anidación de IFs )
        if ($this->hasEmptyFields($fields)) {
            return $this->json('warning', 'Faltan datos obligatorios.');
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


    private function executeRegistration($f, $tempFile = null)
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

    public function validateToken(string $token)
    {
        return $this->coachModel->validateToken($token);
    }



    private function hasEmptyFields($f)
    {
        return empty($f['first_name']) || empty($f['last_name']) || empty($f['password'] || empty($f['phone'] || empty($f['specialty'])));
    }

}
