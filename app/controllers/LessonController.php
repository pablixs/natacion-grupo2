<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Booking.php';

class LessonController extends BaseController
{
    private $pdo;
    private $lessonModel;
    private $bookingModel;

    public function __construct()
    {
        global $pdo;

        $this->pdo = $pdo;
        $this->lessonModel = new Lesson($pdo);
        $this->bookingModel = new Booking($pdo);
    }

    public function available()
    {
        $this->checkAuth(3);

        $lessons = $this->lessonModel->getAvailableLessons();

        $this->render('users/lessons.view', [
            'title' => 'Clases disponibles',
            'lessons' => $lessons
        ]);
    }

    public function enroll()
    {
        $this->checkAuth(3);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return header('Location: ?url=lessons');
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
}