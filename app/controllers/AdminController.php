<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';

class AdminController extends BaseController
{
    private $pdo;
    private $userModel;
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

    public function registerCoach() {
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

    public function register(){
        $this->checkAuth(1);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->index();
        }

        // 1. Recolección y Sanitización ( Evitamos espacios vacíos y basura )
        $fields = [
            'first_name'    => trim($_POST['nombre'] ?? ''),
            'last_name'     => trim($_POST['apellido'] ?? ''),
            'email'          => trim($_POST['email'] ?? ''),
            'password'       => $_POST['password'] ?? '',
            'passwordrepeat' => $_POST['passwordrepeat'] ?? '',
            'phone'          => trim($_POST['telefono'] ?? ''),
            'birth_date' => $_POST['birth_date'],
            'role_id'         => $_POST['role_id'] ?? 2,
            'specialty'      => $_POST['especialidad'] ?? ''
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

        if (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->json('error', 'El email ingresado no es válido.');
        }

        if (strlen($fields['password']) < 6) {
            return $this->json('warning', 'La contraseña es muy corta (mín. 6 caracteres).');
        }

        $tempFile = null;
        if ( isset( $_FILES[ 'profile_image' ] ) && $_FILES[ 'profile_image' ][ 'error' ] === UPLOAD_ERR_OK ) {
            $uploadDir = __DIR__ . '/../../public/img/uploads/profiles/swimmers/';

            if ( !is_dir( $uploadDir ) ) {
                mkdir( $uploadDir, 0755, true );
            }

            $extension = strtolower( pathinfo( $_FILES[ 'profile_image' ][ 'name' ], PATHINFO_EXTENSION ) );
            $allowed = [ 'jpg', 'jpeg', 'png', 'gif' ];

            if ( in_array( $extension, $allowed ) ) {

                // 1. Tomamos la inicial del nombre en minúscula
                $initial = strtolower( substr( $fields[ 'first_name' ], 0, 1 ) );

                // 2. Limpiamos el apellido ( quitamos espacios y pasamos a minúscula )
                $lastName = strtolower( str_replace( ' ', '', $fields[ 'last_name' ] ) );

                // 3. Generamos un número aleatorio de 4 dígitos para evitar colisiones ( Juan Perez vs Jorge Perez )
                $randomNumber = rand( 1000, 9999 );

                // Resultado ej: jperez_4521.jpg
                $newFileName = 'swimmer_' . $initial . $lastName . '_' . $randomNumber . '.' . $extension;
                $absolutePath = $uploadDir . $newFileName;

                if ( move_uploaded_file( $_FILES[ 'profile_image' ][ 'tmp_name' ], $absolutePath ) ) {
                    $fields[ 'profile_image' ] = $newFileName;
                    $tempFile = $absolutePath;
                }
            }
        }

        return $this->executeRegistration($fields, $tempFile);
    }

    /**
     * Lógica de inscripción con Transacción SQL.
     * Enseñamos que si algo falla en el medio, no debe quedar basura en la DB.
     */

    private function executeRegistration($f, $tempFile = null)
    {
        $this->checkAuth(1);

        try {
            if ($this->userModel->findByEmail($f['email'])) {
                if ( $tempFile && file_exists( $tempFile ) ) {
                    unlink( $tempFile );

                }
                return $this->json('user_exists', '.', Env::get('APP_URL') . '/?url=coaches');
            }

            $this->pdo->beginTransaction();

            // Tabla: users
            $userId = $this->userModel->createUser([
                'email'    => $f['email'],
                'password' => $f['password'],
                'role_id'  => $f['role_id'] // Rol Coach
            ]);

            if (!$userId) throw new Exception('Error al crear credenciales.');

            $f['user_id'] = $userId;
            $this->profileModel->create($f);

            $this->pdo->commit();

            // 1. Obtenemos la URL base del .env ( ej: http://localhost/gestion-natacion )
            $baseUrl = rtrim(Env::get('APP_URL'), '/');

            // 2. Si por algún error el .env está vacío, fallamos con una base segura
            if (empty($baseUrl)) {
                $baseUrl = 'http://localhost/natacion-grupo2/';
            }

            // 3. Construimos la URL final
            $coachesUrl = $baseUrl . '/?url=coaches';

            return $this->json('success', '¡Registro completado!', $coachesUrl);
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();

            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }
    }

    private function hasEmptyFields( $f ) {
        return empty( $f[ 'first_name' ] ) || empty( $f[ 'last_name' ] ) || empty( $f[ 'email' ] ) || empty( $f[ 'password' ] || empty ($f['phone'] || empty ($f['specialty']) ));
    }
}
