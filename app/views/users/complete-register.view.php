<?php

/** @var string $token */
/** @var int $role_id */
/** @var array $specialties */
/** @var array $coachSpecialtyIds */
include __DIR__ . '/../users/layout/header.php';
/* Se declara la variable y el tipo para que intelephense no marque error de variable no definida */
/** @var int $coachs_data */
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header primary-bg text-white">
                    <h4 class="mb-0 text-center"><?php echo $title ?? 'Registro de Swimmer'; ?></h4>
                </div>

                <div class="card-body">
                    <form id="formSaveProfile" action="?url=create-coach" method="POST" enctype="multipart/form-data">
                        <div class="row row-cols-1 row-cols-md-2">
                            <input type="text" id="token" value="<?= $token ?>" name="token" class="form-control" placeholder="" hidden
                                required disabled>
                            <div class="col mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Juan"
                                    required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="apellido" class="form-control" placeholder="Ej: Pérez"
                                    required>
                            </div>




                            <div class="col mb-3">
                                <label class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" placeholder="Mín. 6 caracteres" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Repetir contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="passwordrepeat" class="form-control" placeholder="Mín. 6 caracteres" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" placeholder="11 1234 5678" required>
                            </div>

                            <div class="col mb-3 ">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input type="date" name="birth_date" class="form-control"
                                    required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Foto de Perfil</label>
                                <input type="file" name="profile_image" class="form-control" accept="image/*">
                            </div>
                            <?php if ($role_id == 2): ?>
                                <div class="col-12">
                                    <label class="form-label d-block">Especialidades</label>
                                    <div class="specialty-grid">
                                        <?php foreach ($specialties as $specialty) : ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specialties[]"
                                                    value="<?= $specialty['id'] ?>"
                                                    id="regSpec<?= $specialty['id'] ?>">
                                                <label class="form-check-label" for="regSpec<?= $specialty['id'] ?>"><?= $specialty['specialty'] ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="row mt-3 mx-2">
                            <button type="submit" class="btn-submit-form w-100">
                                </i>Completar registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../users/layout/footer.php'; ?>