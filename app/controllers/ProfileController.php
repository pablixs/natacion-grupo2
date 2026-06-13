<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Profile.php';


class ProfileController extends BaseController
{
    private $pdo;
    private Profile $profileModel;

    public function __construct()
    {
        global $pdo;

        $this->pdo = $pdo;
        $this->profileModel = new Profile($pdo);
    }

    public function getEditProfileView()
    {
        $this->checkAuth();

        $profile = $this->profileModel->getSwimmerProfile($_SESSION['user_id']);

        if (!$profile) {
            return header('Location: ?url=home');
        }

        $data = [
            'title'   => 'Editar Perfil',
            'name'    => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3,
            'profile' => $profile
        ];

        $this->render('swimmer/edit-profile.view', $data);
    }

    public function updateProfile()
    {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return header('Location: ?url=profile');
        }

        $userId = $_SESSION['user_id'];

        $fields = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name'  => trim($_POST['last_name'] ?? ''),
            'phone'      => trim($_POST['phone'] ?? ''),
            'birth_date' => trim($_POST['birth_date'] ?? ''),
            'user_id'    => $userId
        ];

        // 2. Validaciones Críticas (Early Returns)
        if ($this->hasEmptyFields($fields)) {
            return $this->json('warning', 'Todos los campos son obligatorios.');
        }

        if (strlen($fields['phone']) < 6 || strlen($fields['phone']) > 15) {
            return $this->json('warning', 'El número de teléfono debe tener de 6 a 15 números.');
        }

        // --- GESTIÓN DE IMAGEN DE PERFIL ---
        $tempFile = null;
        $newFileName = null;

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/img/uploads/profiles/swimmers/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($extension, $allowed)) {
                return $this->json('warning', 'Solo se permiten imágenes JPG o PNG.');
            }

            if ($_FILES['profile_image']['size'] > 2 * 1024 * 1024) {
                return $this->json('warning', 'La imagen no puede superar los 2MB.');
            }

            $initial = strtolower(substr($fields['first_name'], 0, 1));
            $lastName = strtolower(str_replace(' ', '', $fields['last_name']));
            $randomNumber = rand(1000, 9999);

            $newFileName = 'swimmer_' . $initial . $lastName . '_' . $randomNumber . '.' . $extension;
            $absolutePath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $absolutePath)) {
                $tempFile = $absolutePath;
            } else {
                return $this->json('error', 'Error al subir la imagen.');
            }
        }

        return $this->executeProfileUpdate($fields, $newFileName, $tempFile);
    }

    private function executeProfileUpdate($f, $profileImage = null, $tempFile = null)
    {
        try {
            $this->pdo->beginTransaction();

            $oldImage = null;
            if ($profileImage) {
                $oldImage = $this->profileModel->getProfileImage($f['user_id']);
            }

            $this->profileModel->updateSwimmerProfile($f['user_id'], $f, $profileImage);

            $_SESSION['first_name'] = $f['first_name'];

            // $this->activityLog->newLog('profile_updated', ['name' => $f['first_name'] . " " . $f['last_name']]);

            $this->pdo->commit();


            $_SESSION['first_name'] = $f['first_name'];
            if ($profileImage) {
                $_SESSION['profile_image'] = $profileImage;
            }

            if ($profileImage && $oldImage && $oldImage !== 'default-profile.png') {
                $oldPath = __DIR__ . '/../../public/img/uploads/profiles/swimmers/' . $oldImage;
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            return $this->json('success', 'Perfil actualizado correctamente.', '?url=profile');
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();

            // Si algo falló, borramos la foto nueva para no dejar basura
            if ($tempFile && file_exists($tempFile)) {
                unlink($tempFile);
            }

            return $this->json('error', 'No se pudo actualizar: ' . $e->getMessage());
        }
    }

    public function getSwimmerProfileView()
    {
        $this->checkAuth();

        $profile = $this->profileModel->getSwimmerProfile($_SESSION['user_id']);

        $data = [
            'title'   => 'Mi Perfil',
            'name'    => $_SESSION['first_name'] ?? 'Guest',
            'role_id' => $_SESSION['role_id'] ?? 3,
            'profile' => $profile
        ];

        $this->render('swimmer/profile.view', $data);
    }

    private function hasEmptyFields($f)
    {
        return empty($f['first_name']) || empty($f['last_name']) || empty($f['phone'] || empty($f['birth_date']));
    }
}
