<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Coach.php';

class LessonController extends BaseController
{
    private $pdo;
    private $lessonModel;
    private $bookingModel;
    private $coachModel;


    public function __construct()
    {
        global $pdo;

        $this->pdo = $pdo;
        $this->lessonModel = new Lesson($pdo);
        $this->bookingModel = new Booking($pdo);
        $this->coachModel = new Coach($pdo);

    }

     public function coachHomeView()
    {
        $this->checkAuth(2);

        $lessons = $this->coachModel->getCoachHome($_SESSION['user_id']);

        $data = [
            'title'   => 'Home',
            'name'    => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 2,
            'lessons' => $lessons
        ];

        $this->render('coach/home.view', $data);
    }

    public function coachLessonsView()
    {
        $this->checkAuth(2);

        $lessons = $this->coachModel->getCoachHome($_SESSION['user_id']);

        $data = [
            'title'   => 'Mis Clases',
            'name'    => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 2,
            'lessons' => $lessons
        ];

        $this->render('coach/coach-lessons.view', $data);
    }

    public function getLessonStudents()
    {
        $this->checkAuth(2);

        $lessonId = $_GET['lesson_id'] ?? null;

        if (!$lessonId) {
            return $this->json('error', 'ID de clase no proporcionado.');
        }

        $students = $this->coachModel->getLessonStudents($lessonId);

        header('Content-Type: application/json');
        echo json_encode([
            'status'   => 'success',
            'students' => $students
        ]);
    }

    public function swimmerHomeView()
    {
        $this->checkAuth();

        $lessons = $this->lessonModel->getSwimmerHome($_SESSION['user_id']);

        $data = [
            'title'   => "Home",
            'name'    => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3,
            'lessons' => $lessons
        ];

        $this->render('swimmer/home.view', $data);
    }

    public function getEnrolledLessonsView()
    {
        $this->checkAuth();

        $lessons = $this->lessonModel->getEnrolledLessonsByUserId($_SESSION['user_id']);

        $data = [
            'title' => 'Clases disponibles',
            'lessons' => $lessons,
            'module' => 'MainLessons',
            'role_id' => $_SESSION['role_id'] ?? 3,
        ];

        $this->render('swimmer/lessons.view', $data);
    }

    public function getAvailableLessonsView()
    {
        $this->checkAuth();

        $lessons = $this->lessonModel->getLessonsForSwimmer($_SESSION['user_id']);

        $data = [
            'title' => 'Clases disponibles',
            'lessons' => $lessons,
            'module' => 'MainLessons'
        ];

        $this->render('swimmer/enroll-lesson.view', $data);
    }

    public function enrollLessonPost()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return header('Location: ?url=lesson-enroll');
        }

        $swimmerId = $_SESSION['user_id'];
        $lessonId = $_POST['lesson_id'] ?? null;

        if (!$lessonId) {
            return $this->json('warning', 'No se seleccionó ninguna clase.');
        }

        if ($this->bookingModel->alreadyBooked($swimmerId, $lessonId)) {
            return $this->json('warning', 'Ya estás inscripto en esta clase.');
        }

        try {
            $this->bookingModel->create($swimmerId, $lessonId);

            return $this->json(
                'success',
                'Inscripción realizada correctamente.',
                Env::get('APP_URL') . '/?url=lessons'
            );
        } catch (Exception $e) {
            return $this->json('error', 'No se pudo realizar la inscripción.');
        }
    }

    public function unenrollLessonPost()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return header('Location: ?url=lesson-enroll');
        }

        $swimmerId = $_SESSION['user_id'];
        $lessonId = $_POST['lesson_id'] ?? null;

        if (!$lessonId) {
            return $this->json('warning', 'No se seleccionó ninguna clase.');
        }
        try {
            $this->bookingModel->unenrollSwimmer($lessonId, $swimmerId);

            return $this->json(
                'success',
                'Baja realizada correctamente.',
                Env::get('APP_URL') . '/?url=lessons'
            );
        } catch (Exception $e) {
            return $this->json('error', 'No se pudo realizar la inscripción.');
        }
    }
}
