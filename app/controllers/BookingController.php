<?php
require_once __DIR__ . '/../models/Booking.php';

class BookingController extends BaseController
{
    private $pdo;
    private $bookingModel;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        $this->bookingModel = new Booking($pdo);
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
