<?php include __DIR__ . '/../administrator/layout/header.php';
/** @var array $coaches */
/** @var array $specialties */
?>

<div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 680px;">

                <div class="form-card">
                    <div class="form-card-header d-flex justify-content-between align-items-center">
                        <h5></i>Crear una nueva clase</h5>
                        <a href="?url=manage-lessons" class="btn-outline-navy text-white" style="padding: 0.35rem 1rem; font-size: 0.8rem;">
                            <i class="fa-solid fa-arrow-left"></i>Volver
                        </a>
                    </div>
                    <div class="form-card-body">
                        <form id="formNewLesson" action="?url=create-lesson" method="POST" enctype="multipart/form-data">
                            <div class="row g-4">

                                <div class="col-md-6">
                                    <label class="form-label">Profesor</label>
                                    <select class="form-select" name="coach_id" required>
                                        <option value="" selected disabled>Seleccionar profesor</option>
                                        <?php foreach ($coaches as $coach) : ?>
                                            <option value="<?= $coach['id'] ?>">Prof. <?= $coach['full_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nivel</label>
                                    <select class="form-select" name="level" required>
                                        <option value="" selected disabled>Seleccionar nivel</option>
                                        <option value="Principiante">Principiante</option>
                                        <option value="Intermedio">Intermedio</option>
                                        <option value="Avanzado">Avanzado</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label d-block">Especialidades</label>
                                    <div class="specialty-grid">
                                        <?php foreach ($specialties as $specialty) : ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="specialties[]" value="<?= $specialty['id'] ?>" id="<?= $specialty['specialty'] ?>">
                                                <label class="form-check-label" for="<?= $specialty['specialty'] ?>"><?= $specialty['specialty'] ?></label>
                                            </div>   
                                        <?php endforeach; ?>
                                    
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Primer día</label>
                                    <select class="form-select" name="first_day_of_week">
                                        <option value="">Seleccionar día</option>
                                        <option value="Lunes">Lunes</option>
                                        <option value="Martes">Martes</option>
                                        <option value="Miércoles">Miércoles</option>
                                        <option value="Jueves">Jueves</option>
                                        <option value="Viernes">Viernes</option>
                                        <option value="Sábado">Sábado</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Segundo día</label>
                                    <select class="form-select" name="second_day_of_week">
                                        <option value="">Ninguno</option>
                                        <option value="Lunes">Lunes</option>
                                        <option value="Martes">Martes</option>
                                        <option value="Miércoles">Miércoles</option>
                                        <option value="Jueves">Jueves</option>
                                        <option value="Viernes">Viernes</option>
                                        <option value="Sábado">Sábado</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hora inicio</label>
                                    <input type="time" class="form-control" name="start_time" value="08:00">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hora fin</label>
                                    <input type="time" class="form-control" name="end_time" value="09:00">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Capacidad</label>
                                    <input type="number" class="form-control" name="capacity" min="1" value="20">
                                </div>

                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="active" id="active" checked>
                                        <label class="form-check-label" for="active">Clase activa</label>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn-submit">
                                        <i class="fa-solid fa-plus me-2"></i>Crear Clase
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