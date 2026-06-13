<?php
include __DIR__ . '/../swimmer/layout/header.php';
/** @var array $profile */
$currentUrl = $_GET['url'] ?? 'home';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 900px;">

            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar-wrapper">
                        <?php
                        $foto = $_SESSION['profile_image'] ?? 'default-profile.png';
                        $rutaFoto = Env::get('ASSET_URL') . "/img/uploads/profiles/swimmers/" . $foto;
                        ?>
                        <img
                            src="<?= $rutaFoto ?>"
                            alt="Foto de perfil"
                            class="profile-avatar">
                    </div>
                    <h4 class="profile-name"><?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?></h4>
                    <?php
                    $roleLabel = match ((int)$_SESSION['role_id']) {
                        1 => 'Administrador',
                        2 => 'Profesor',
                        3 => 'Alumno',
                        default => 'Usuario',
                    };
                    ?>
                    <span class="badge badge-role"><?= $roleLabel ?></span>
                </div>

                <div class="profile-body">
                    <div class="section-label">Información personal</div>

                    <div class="profile-info-list">
                        <div class="profile-info-item">
                            <div class="profile-info-icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <span class="profile-info-label">Email</span>
                                <span class="profile-info-value"><?= htmlspecialchars($profile['email']) ?></span>
                            </div>
                        </div>

                        <div class="profile-info-item">
                            <div class="profile-info-icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <span class="profile-info-label">Teléfono</span>
                                <span class="profile-info-value"><?= htmlspecialchars($profile['phone']) ?></span>
                            </div>
                        </div>

                        <div class="profile-info-item">
                            <div class="profile-info-icon">
                                <i class="fa-solid fa-cake-candles"></i>
                            </div>
                            <div>
                                <span class="profile-info-label">Fecha de nacimiento</span>
                                <span class="profile-info-value">
                                    <?= date('d/m/Y', strtotime($profile['birth_date'])) ?>
                                </span>
                            </div>
                        </div>

                        <?php if (!empty($profile['specialty'])): ?>
                            <div class="profile-info-item">
                                <div class="profile-info-icon">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div>
                                    <span class="profile-info-label">Especialidad</span>
                                    <span class="profile-info-value"><?= htmlspecialchars($profile['specialty']) ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="profile-info-item">
                            <div class="profile-info-icon">
                                <i class="fa-solid fa-calendar"></i>
                            </div>
                            <div>
                                <span class="profile-info-label">Miembro desde</span>
                                <span class="profile-info-value">
                                    <?= date('d/m/Y', strtotime($profile['created_at'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="?url=edit-profile" class="btn-submit-form w-100 d-block text-center text-decoration-none">
                            <i class="fa-solid fa-pen me-2"></i>Editar perfil
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../swimmer/layout/footer.php'; ?>