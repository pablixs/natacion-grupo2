<?php
include __DIR__ . '/../swimmer/layout/header.php';
/** @var array $profile */
$currentUrl = $_GET['url'] ?? 'edit-profile';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 900px;">

            <div class="form-card">
                <div class="form-card-header">
                    <a href="?url=profile" class="back-link">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h5 class="mb-0">Editar perfil</h5>
                </div>

                <div class="form-card-body">
                    <form id="formEditProfile" enctype="multipart/form-data">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">


                        <div class="edit-avatar-section mb-4">
                            <div class="edit-avatar-wrapper">
                                <?php
                                $foto = $_SESSION['profile_image'] ?? 'default-profile.png';
                                $rutaFoto = Env::get('ASSET_URL') . "/img/uploads/profiles/swimmers/" . $foto;
                                ?>
                                <img
                                    id="avatarPreview"
                                    src="<?= $rutaFoto ?>"
                                    alt="Foto de perfil"
                                    class="edit-avatar"
                                    onerror="this.src='img/uploads/profiles/swimmers/default-profile.png'">
                                <label for="profile_image" class="edit-avatar-overlay">
                                    <i class="fa-solid fa-camera"></i>
                                </label>
                            </div>
                            <input
                                type="file"
                                name="profile_image"
                                id="profile_image"
                                class="d-none"
                                accept="image/png, image/jpeg, image/jpg">
                            <small class="text-muted mt-2 d-block text-center">JPG o PNG. Máximo 2MB.</small>
                        </div>

                        <!-- Nombre y Apellido -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">Nombre</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="first_name"
                                    name="first_name"
                                    value="<?= htmlspecialchars($profile['first_name']) ?>"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Apellido</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="last_name"
                                    name="last_name"
                                    value="<?= htmlspecialchars($profile['last_name']) ?>"
                                    required>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input
                                type="tel"
                                class="form-control"
                                id="phone"
                                name="phone"
                                value="<?= htmlspecialchars($profile['phone']) ?>"
                                required>
                        </div>

                        <!-- Fecha de nacimiento -->
                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Fecha de nacimiento</label>
                            <input
                                type="date"
                                class="form-control"
                                id="birth_date"
                                name="birth_date"
                                value="<?= htmlspecialchars($profile['birth_date']) ?>"
                                required>
                        </div>

                        <!-- Email (solo lectura) -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                value="<?= htmlspecialchars($profile['email']) ?>"
                                disabled>
                            <small class="text-muted">El email no se puede modificar.</small>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-3">
                            <a href="?url=profile" class="btn-cancel-form w-50">Cancelar</a>
                            <button type="submit" class="btn-submit-form w-50">
                                <i class="fa-solid fa-check me-2"></i>Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../swimmer/layout/footer.php'; ?>