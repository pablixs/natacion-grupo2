<?php
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';
require_once __DIR__ . '/../models/Lesson.php';

class BookingController extends BaseController
{
    private $pdo;
    private $bookingModel;
    private $activityLog;
    private $lessonModel;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
        $this->bookingModel = new Booking($pdo);
        $this->lessonModel = new Lesson($pdo);
        $this->activityLog = new SaveActivityLog();

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


            $lesson = $this->lessonModel->getLessonById($lessonId);

            $this->activityLog->newLog('swimmer_enrolled', [
                'name'       => $_SESSION['first_name'],
                'class_name' => $lesson['level'] . ' - ' . $lesson['first_day_of_week'] . ' y ' . $lesson['second_day_of_week']
            ]);

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
