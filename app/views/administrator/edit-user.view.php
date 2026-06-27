<?php
include __DIR__ . '/../administrator/layout/header.php';
/** @var array $userData */
/** @var string $back_url */
/** @var array $specialties */
/** @var array $coachSpecialtyIds */
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 680px;">

            <div class="form-card">
                <div class="form-card-header d-flex justify-content-between align-items-center">
                    <h5>Editar usuario</h5>
                    <a href="<?= $back_url ?>" class="btn-outline-navy text-white" style="padding: 0.35rem 1rem; font-size: 0.8rem;">
                        <i class="fa-solid fa-arrow-left"></i>Volver
                    </a>
                </div>
                <div class="form-card-body">
                    <form id="formEditUser">
                        <input type="hidden" name="user_id" value="<?= $userData['id'] ?>">
                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="first_name"
                                    value="<?= htmlspecialchars($userData['first_name'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="last_name"
                                    value="<?= htmlspecialchars($userData['last_name'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="phone"
                                    value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rol</label>
                                <select class="form-select" name="role_id" required>
                                    <option value="1" <?= $userData['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                                    <option value="2" <?= $userData['role_id'] == 2 ? 'selected' : '' ?>>Profesor</option>
                                    <option value="3" <?= $userData['role_id'] == 3 ? 'selected' : '' ?>>Alumno</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input type="date" class="form-control" name="birth_date"
                                    value="<?= $userData['birth_date'] ?? '' ?>">
                            </div>

                            <?php if ($userData['role_id'] == 2): ?>
                                <div class="col-12">
                                    <label class="form-label d-block">Especialidades</label>
                                    <div class="specialty-grid">
                                        <?php foreach ($specialties as $specialty) : ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specialties[]"
                                                    value="<?= $specialty['id'] ?>"
                                                    id="coachSpec<?= $specialty['id'] ?>"
                                                    <?= in_array($specialty['id'], $coachSpecialtyIds) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="coachSpec<?= $specialty['id'] ?>"><?= $specialty['specialty'] ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn-submit">
                                    Guardar cambios
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../administrator/layout/footer.php'; ?>