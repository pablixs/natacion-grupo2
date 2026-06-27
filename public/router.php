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
$route = $_GET[ 'url' ] ?? 'landing';

/**
* 2. DESPACHO DE RUTAS ( DISPATCHER )
* El switch actúa como una tabla de decisiones.
*/
switch ( $route ) {

    case 'landing':
        require_once __DIR__ . '/../app/controllers/HomeController.php';
        ( new HomeController() )->landing();
        break;

    // --- VISTA PRINCIPAL ---
    case 'home':
        if (isset($_SESSION['role_id'])) {
            switch ($_SESSION['role_id']){
                case 2: 
                    require_once __DIR__ . '/../app/controllers/CoachController.php';
                    $controller = new CoachController();
                    $controller->coachHomeView();
                break;
                case 3: 
                    require_once __DIR__ . '/../app/controllers/LessonController.php';
                    $controller = new LessonController();
                    $controller->swimmerHomeView();
                break;
                default:
                    require_once __DIR__ . '/../app/controllers/HomeController.php';
                    ( new HomeController() )->index();
                break;
            }
        } else {
            require_once __DIR__ . '/../app/controllers/HomeController.php';
            ( new HomeController() )->landing();
        }
    break;

    // --- MÓDULO DE USUARIOS Y AUTENTICACIÓN ---
    // Agrupamos rutas relacionadas para evitar repetir el require_once
    case 'authenticate':
    case 'forgot-password':
    case 'send-reset':
    case 'reset-password':
    case 'update-password':
    case 'login':
    case 'complete-register':
    case 'save-profile':

        require_once __DIR__ . '/../app/controllers/UserController.php';
        $controller = new UserController();

        /**
        * Ejecución del método según la acción solicitada.
        * Separamos la visualización ( GET ) de la lógica de procesamiento ( POST ).
        */
        if ( $route === 'login' )           $controller->showLogin();
        if ( $route === 'authenticate' )    $controller->authenticate();
        if ( $route === 'forgot-password' ) $controller->forgotPassword();
        if ( $route === 'send-reset' )      $controller->sendReset();
        if ( $route === 'reset-password' )  $controller->showResetForm();
        if ( $route === 'update-password' ) $controller->updatePassword();
        
        if($route === 'complete-register') $controller->completeRegistrationView();
        if($route === 'save-profile') $controller->completeRegistrationPost();
    break;

    // Rutas de lessons

    case 'lessons':
    case 'lesson-enroll':
    case 'lesson-enroll-new':
    case 'lesson-unenroll':

    case 'coach-lessons':
    case 'lesson-students':
        require_once __DIR__ . '/../app/controllers/LessonController.php';
        $controller = new LessonController();

        if($route === 'lessons') $controller->getEnrolledLessonsView();
        if($route === 'lesson-enroll') $controller->getAvailableLessonsView();
        if($route === 'lesson-enroll-new') $controller->enrollLessonPost();
        if($route === 'lesson-unenroll') $controller->unenrollLessonPost();

        if($route === 'coach-lessons') $controller->coachLessonsView();
        if($route === 'lesson-students') $controller->getLessonStudents();
    break;



    // Rutas de perfil

    case 'profile':
    case 'edit-profile':
    case 'update-profile':
        require_once __DIR__ . '/../app/controllers/ProfileController.php';
        $controller = new ProfileController();

        if($route === 'profile') $controller->getSwimmerProfileView();
        if($route === 'edit-profile') $controller->getEditProfileView();
        if($route === 'update-profile') $controller->updateProfile();

    break;



    // Rutas de admin
    case 'coaches':
    case 'register-coach': // Vista del form de registro 
    case 'create-coach': // POST para la creacion del couch
    case 'swimmers':
    case 'register-swimmer':
    case 'create-swimmer':
    case 'manage-lessons':
    case 'new-lesson': // Vista del form de creación de clase
    case 'create-lesson': // POST para la creación de la clase
    // case 'test-mail':

    //test
    // case 'manage-users-get':

        require_once __DIR__ . '/../app/controllers/AdminController.php';
        $controller = new AdminController();
        
        if($route === 'coaches') $controller->coachesView();
        if($route === 'register-coach') $controller->registerCoachView(); // Renderiza la vista del form para registrar un coach
        if($route === 'create-coach') $controller->registerCoachPost();  // POST para crear el coach

        if($route === 'swimmers') $controller->swimmersView();
        if($route === 'register-swimmer') $controller->registerSwimmerView(); // Renderiza la vista del form para registrar un swimmer
        if($route === 'create-swimmer') $controller->registerSwimmerPost();  // POST para crear el swimmer


        // if($route === 'test-mail') $controller->testSendEmail('cristiandaniiel3@gmail.com');

        if($route === 'manage-users-get') $controller->getUsersAndProfiles();

        
        // Llevar a su propio controlador LessonsController()
        if($route === 'manage-lessons') $controller->manageLessonsView();
        if($route === 'new-lesson') $controller->newLessonView();
        if($route === 'create-lesson') $controller->newLessonPost();

    break;

    // Rutas de profile -> Coach

    
    //break;



    // --- SEGURIDAD: CIERRE DE SESIÓN ---
    case 'logout':
    /**
    * Para destruir una sesión, primero debemos estar seguros de que
    * el sistema sabe de su existencia ( iniciada previamente en index.php ).
    */
    $_SESSION = [];
    // Vaciamos el array de sesión por seguridad
    session_destroy();
    // Eliminamos el archivo de sesión en el servidor

    // Redirigimos al Login para forzar una nueva autenticación
    header( 'Location: ?url=login' );
    exit;
    // Detenemos el script para asegurar la redirección

    // --- MANEJO DE ERRORES ---
    default:
    /**
    * Si el usuario intenta acceder a una ruta que no definimos arriba,
    * devolvemos un código de estado 404 ( Not Found ).
    */
    http_response_code( 404 );
    echo 'Error 404: La página "' . htmlspecialchars( $route ) . '" no existe en este sistema.';
    break;
}