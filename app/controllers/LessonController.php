<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Lesson.php';
require_once __DIR__ . '/../models/Coach.php';
require_once __DIR__ . '/../models/Profile.php';
require_once __DIR__ . '/../utils/SaveActivityLog.php';

class LessonController extends BaseController
{
    private $pdo;
    private $lessonModel;
    private $coachModel;
    private $profileModel;
    private $activityLog;


    public function __construct()
    {
        global $pdo;

        $this->pdo = $pdo;
        $this->lessonModel = new Lesson($pdo);
        $this->profileModel = new Profile($pdo);
        $this->coachModel = new Coach($pdo);
        $this->activityLog = new SaveActivityLog();
    }

    public function manageLessonsView()
    {
        $this->checkAuth(1);

        $lessons     = $this->lessonModel->getLessons();
        $coaches     = $this->profileModel->getAllDataByRole(2);
        $specialties = $this->lessonModel->getSpecialties();

        $data = [
            'title'       => 'Gestión de Clases',
            'user'        => $_SESSION['email'] ?? 'Guest',
            'name'        => $_SESSION['first_name'] ?? 'Guest',
            'role_id'     => $_SESSION['role_id'] ?? 1,
            'lessons'     => $lessons,
            'coaches'     => $coaches,
            'specialties' => $specialties
        ];

        $this->render('administrator/manage-lessons.view', $data);
    }

    public function newLessonView()
    {
        $this->checkAuth(1);


        $coaches = $this->profileModel->getAllDataByRole(2);
        $specialties = $this->lessonModel->getSpecialties();

        $data = [
            'title' => "Manage Users Dashboard",
            'user'  => $_SESSION['email'] ?? 'Guest',
            'name' => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 1,
            'coaches' => $coaches,
            'specialties' => $specialties
        ];



        $this->render('administrator/new-lesson.view', $data);
    }

    public function newLessonPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return http_response_code(405);
        }

        // // 1. Recolección y Sanitización ( Evitamos espacios vacíos y basura )



        $fields = [
            'coach_id' => trim($_POST['coach_id'] ?? ''),
            'level' => trim($_POST['level'] ?? ''),
            'specialties' =>  $_POST['specialties'] ?? [],
            'first_day_of_week' => trim($_POST['first_day_of_week'] ?? ''),
            'second_day_of_week' => trim($_POST['second_day_of_week'] ?? null),
            'start_time' => trim($_POST['start_time'] ?? ''),
            'end_time' => trim($_POST['end_time'] ?? ''),
            'capacity' => trim($_POST['capacity'] ?? ''),
            'active' => isset($_POST['active']) ? 1 : 0

        ];

        $days = [
            "Lunes",
            "Martes",
            "Miercoles",
            "Jueves",
            "Viernes",
            "Sabado"
        ];

        $levels = [
            "Principiante",
            "Intermedio",
            "Avanzado"
        ];


        if (empty($fields['coach_id']) || empty($fields['level']) || empty($fields['specialties']) || empty($fields['first_day_of_week']) || empty($fields['start_time']) || empty($fields['end_time']) || empty($fields['capacity'])) {
            return $this->json('warning', 'Faltan datos obligatorios.');
        }

        if (!in_array($fields['level'], $levels)) {
            return $this->json('error', 'Nivel no válido.');
        }

        if (!in_array($fields['first_day_of_week'], $days)) {
            return $this->json('error', 'Primer día de la semana no válido.');
        }

        if (!is_null($fields['second_day_of_week']) && !in_array($fields['second_day_of_week'], $days)) {
            return $this->json('error', 'Segundo día de la semana no válido.');
        }

        try {
            $created = $this->lessonModel->createClass($fields);

            if (!$created) {
                return $this->json('error', 'No se pudo crear la clase.');
            }

            $this->activityLog->newLog('class_created', ['class_name' => $fields['level'] . ' - ' . $fields['first_day_of_week'] . ' y ' . $fields['second_day_of_week']]);
            return $this->json('success', '¡Clase creada!');
        } catch (Exception $e) {
            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }
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
        $this->checkAuth();

        // Permitir solo admin y coach
        $role = $_SESSION['role_id'] ?? 0;
        if (!in_array($role, [1, 2])) {
            return $this->json('error', 'No tenés permiso para ver esta información.');
        }

        $lessonId = intval($_GET['lesson_id'] ?? 0);

        if ($lessonId <= 0) {
            return $this->json('error', 'ID de clase no proporcionado.');
        }

        $students = $this->lessonModel->getStudentsByLessonId($lessonId);

        header('Content-Type: application/json');
        echo json_encode([
            'status'   => 'success',
            'students' => $students
        ]);
        exit;
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




    public function getLessonData()
    {
        $this->checkAuth(1);

        $lessonId = intval($_GET['id'] ?? 0);

        if ($lessonId <= 0) {
            return $this->json('error', 'ID de clase inválido.');
        }

        $lesson = $this->lessonModel->getLessonById($lessonId);

        if (!$lesson) {
            return $this->json('error', 'Clase no encontrada.');
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'lesson' => $lesson]);
        exit;
    }

    public function editLessonPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return http_response_code(405);
        }

        $fields = [
            'id'                 => intval($_POST['lesson_id'] ?? 0),
            'coach_id'           => trim($_POST['coach_id'] ?? ''),
            'level'              => trim($_POST['level'] ?? ''),
            'specialties'        => $_POST['specialties'] ?? [],
            'first_day_of_week'  => trim($_POST['first_day_of_week'] ?? ''),
            'second_day_of_week' => trim($_POST['second_day_of_week'] ?? '') ?: null,
            'start_time'         => trim($_POST['start_time'] ?? ''),
            'end_time'           => trim($_POST['end_time'] ?? ''),
            'capacity'           => trim($_POST['capacity'] ?? ''),
            'active'             => isset($_POST['active']) ? 1 : 0
        ];

        if ($fields['id'] <= 0) {
            return $this->json('error', 'ID de clase inválido.');
        }

        if (empty($fields['coach_id']) || empty($fields['level']) || empty($fields['specialties']) || empty($fields['first_day_of_week']) || empty($fields['start_time']) || empty($fields['end_time']) || empty($fields['capacity'])) {
            return $this->json('warning', 'Faltan datos obligatorios.');
        }

        if ($fields['capacity'] < 1) {
            return $this->json('warning', 'La capacidad debe ser al menos 1.');
        }

        $levels = ['Principiante', 'Intermedio', 'Avanzado'];
        $days   = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];

        if (!in_array($fields['level'], $levels)) {
            return $this->json('error', 'Nivel no válido.');
        }

        if (!in_array($fields['first_day_of_week'], $days)) {
            return $this->json('error', 'Primer día de la semana no válido.');
        }

        if (!is_null($fields['second_day_of_week']) && !in_array($fields['second_day_of_week'], $days)) {
            return $this->json('error', 'Segundo día de la semana no válido.');
        }

        try {
            $updated = $this->lessonModel->updateClass($fields);

            if (!$updated) {
                return $this->json('error', 'No se pudo actualizar la clase.');
            }

            return $this->json('success', '¡Clase actualizada correctamente!', '?url=manage-lessons');
        } catch (Exception $e) {
            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }
    }
    public function deleteLessonPost()
    {
        $this->checkAuth(1);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return http_response_code(405);
        }

        $lessonId = intval($_POST['lesson_id'] ?? 0);

        if ($lessonId <= 0) {
            return $this->json('error', 'ID de clase inválido.');
        }

        try {
            $lesson = $this->lessonModel->getLessonById($lessonId);
    
            if (!$lesson) {
                return $this->json('error', 'Clase no encontrada.');
            }

            $deleted = $this->lessonModel->deleteLesson($lessonId);

            if (!$deleted) {
                return $this->json('error', 'No se pudo eliminar la clase.');
            }

            $this->activityLog->newLog('class_deleted', ['class_name' => $lesson['level'] . ' - ' . $lesson['first_day_of_week'] . ' y ' . $lesson['second_day_of_week']]);
            return $this->json('success', 'Clase eliminada correctamente.', '?url=manage-lessons');
        } catch (Exception $e) {
            return $this->json('error', 'No se pudo completar: ' . $e->getMessage());
        }
    }
}
