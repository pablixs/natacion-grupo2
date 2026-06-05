<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';

class SwimmerController extends BaseController
{
    private $pdo;
    private $userModel;
    private $activityLog;

    public function __construct()
    {
        /** * Usamos 'global $pdo' porque la conexión se crea en otro archivo ( ej. index o database ).
         * Sin esto, el controlador no tendría acceso al objeto de conexión PDO para pasárselo a los modelos.
         * Es una forma de 'inyectar' la base de datos sin volver a conectarse en cada clase.
         */
        global $pdo;
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
        $this->activityLog = new SaveActivityLog($pdo);
    }

    
    public function enrollLessonView(){
        $this->checkAuth();

        $data = [
            'title' => "Inscribirme a una clase - Alpine Natación",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3,
        ];

        $this->render('swimmer/enroll-lesson.view', $data);
    }

    public function lessonsHistoryView(){
        $this->checkAuth();

        $data = [
            'title' => "Historial de inscripciones - Alpine Natación",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3,
        ];

        $this->render('swimmer/lessons-history.view', $data);
    }


    /**
     * Lógica de inscripción con Transacción SQL.
     * Enseñamos que si algo falla en el medio, no debe quedar basura en la DB.
     */



    private function hasEmptyFields(array $f)
    {
        return empty($f['first_name']) || empty($f['last_name']) || empty($f['email']) || empty($f['password'] || empty($f['phone'] || empty($f['specialty'])));
    }

    // public function testSendEmail(string $email)
    // {
    //     try {

    //         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //             return $this->index();
    //         }

    //         require_once __DIR__ . '/../services/MailService.php';
    //         $mailService = new MailService();

    //         $enviado = $mailService->sendEmailCompleteProfile($email, $token = 'dou');

    //         return $this->json('success', 'Piolita', $enviado);
    //     } catch (Exception $e) {
    //         return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
    //     }
    // }
}
