<?php

/**
 * EL ENRUTADOR ( ROUTER ) - Front Controller Pattern
 * * Este archivo es el único punto de entrada a la lógica del servidor.
 * Su función es leer la intención del usuario ( vía URL ) y delegar el
 * trabajo al controlador correspondiente.
 */

// Cargamos el núcleo del sistema una sola vez
require_once __DIR__ . '/../app/config/db.php';
require_once __DIR__ . '/../app/core/Env.php';
require_once __DIR__ . '/../app/core/BaseController.php';

/**
 * 1. CAPTURA DE LA INTENCIÓN
 * Usamos el parámetro 'url' definido en el .htaccess o pasado por GET.
 * Si no hay ruta ( página de inicio ), por defecto vamos a 'home'.
 */
$route = $_GET['url'] ?? 'landing';

/**
 * 2. DESPACHO DE RUTAS ( DISPATCHER )
 * El switch actúa como una tabla de decisiones.
 */
switch ($route) {
    case 'landing':
        require_once __DIR__ . '/../app/controllers/LandingController.php';
        (new LandingController())->home();
        break;

    // HOME - Dashboard segun rol
    case 'home':
        if (!isset($_SESSION['role_id'])) {
            header('Location: ?url=login');
            exit;
        }

        require_once __DIR__ . '/../app/controllers/HomeController.php';
        $controller = new HomeController();

        switch ($_SESSION['role_id']) {
            case 1:
                $controller->adminHome();
                break;
            case 2:
                $controller->coachHome();
                break;
            case 3:
                $controller->swimmerHome();
                break;
        }
        break;

    // AUTH - Login y recuperacion de contraseña
    case 'login':
    case 'register':
    case 'create-account':
    case 'authenticate':
    case 'forgot-password':
    case 'send-reset':
    case 'reset-password':
    case 'update-password':

        require_once __DIR__ . '/../app/controllers/AuthController.php';
        $controller = new AuthController();

        if ($route === 'login')           $controller->showLogin();
        if ($route === 'register')           $controller->showRegister();
        if ($route === 'create-account')           $controller->createAccount();
        if ($route === 'authenticate')    $controller->authenticate();
        if ($route === 'forgot-password') $controller->forgotPassword();
        if ($route === 'send-reset')      $controller->sendReset();
        if ($route === 'reset-password')  $controller->showResetForm();
        if ($route === 'update-password') $controller->updatePassword();
        break;

    case 'logout':
        $_SESSION = [];
        session_destroy();
        header('Location: ?url=login');
        exit;
        break;

    // REGISTRATION - Alta de usuarios y completar perfil
    case 'register-coach':
    case 'create-coach':
    case 'register-swimmer':
    case 'create-swimmer':
    case 'complete-register':
    case 'save-profile':

        require_once __DIR__ . '/../app/controllers/RegistrationController.php';
        $controller = new RegistrationController();

        // --- Dar de alta un usuario (admin) ---
        if ($route === 'register-coach')    $controller->registerCoachView();
        if ($route === 'create-coach')      $controller->registerCoachPost();
        if ($route === 'register-swimmer')  $controller->registerSwimmerView();
        if ($route === 'create-swimmer')    $controller->registerSwimmerPost();

        // --- Completar perfil (usuario invitado por mail) ---
        if ($route === 'complete-register') $controller->completeRegistrationView();
        if ($route === 'save-profile')      $controller->completeRegistrationPost();
        break;

    // USERS - Listado y consulta de usuarios
    case 'coaches':
    case 'swimmers':
    case 'manage-users-get':
    case 'edit-user':
    case 'update-user':
    case 'delete-user':
    case 'activate-user':
    case 'admin-reset-password':

        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();

        if ($route === 'coaches')          $controller->coachesView();
        if ($route === 'swimmers')         $controller->swimmersView();
        if ($route === 'manage-users-get') $controller->getUsersAndProfiles();
        if ($route === 'edit-user')            $controller->editUserView();
        if ($route === 'update-user')          $controller->updateUserPost();
        if ($route === 'delete-user')          $controller->softDeleteUserPost();
        if ($route === 'activate-user')          $controller->enableUserPost();
        if ($route === 'admin-reset-password') $controller->sendPasswordResetPost();
        break;

    // LESSONS - CRUD y visualizacion de clases
    case 'manage-lessons':
    case 'new-lesson':
    case 'create-lesson':
    case 'get-lesson':
    case 'update-lesson':
    case 'delete-lesson':
    case 'coach-lessons':
    case 'lesson-students':
    case 'lessons':
    case 'lesson-enroll':

        require_once __DIR__ . '/../app/controllers/LessonController.php';
        $controller = new LessonController();

        // --- Admin: gestion de clases ---
        if ($route === 'manage-lessons')  $controller->manageLessonsView();
        if ($route === 'new-lesson')      $controller->newLessonView();
        if ($route === 'create-lesson')   $controller->newLessonPost();
        if ($route === 'get-lesson')      $controller->getLessonData();
        if ($route === 'update-lesson')   $controller->editLessonPost();
        if ($route === 'delete-lesson')   $controller->deleteLessonPost();

        // --- Coach / Admin: ver alumnos de una clase ---
        if ($route === 'lesson-students') $controller->getLessonStudents();

        // --- Coach: ver sus clases y alumnos ---
        if ($route === 'coach-lessons')   $controller->coachLessonsView();

        // --- Swimmer: ver clases inscriptas y disponibles ---
        if ($route === 'lessons')         $controller->getEnrolledLessonsView();
        if ($route === 'lesson-enroll')   $controller->getAvailableLessonsView();
        break;

    // BOOKINGS - Inscripción y baja en clases
    case 'lesson-enroll-new':
    case 'lesson-unenroll':

        require_once __DIR__ . '/../app/controllers/BookingController.php';
        $controller = new BookingController();

        if ($route === 'lesson-enroll-new') $controller->enrollLessonPost();
        if ($route === 'lesson-unenroll')   $controller->unenrollLessonPost();
        break;

    // PROFILE - Ver y editar perfil
    case 'profile':
    case 'edit-profile':
    case 'update-profile':

        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $controller = new ProfileController();

        if ($route === 'profile')        $controller->getSwimmerProfileView();
        if ($route === 'edit-profile')   $controller->getEditProfileView();
        if ($route === 'update-profile') $controller->updateProfile();
        break;

    // 404
    default:
        http_response_code(404);
        echo 'Error 404: La página "' . htmlspecialchars($route) . '" no existe en este sistema.';
        break;
}
