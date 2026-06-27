<?php include __DIR__ . '/../administrator/layout/header.php';
/** @var array $lessons */
/** @var array $coaches */
/** @var array $specialties */
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 1100px;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 text-navy">Gestión de Clases</h4>
                <a href="?url=new-lesson" class="btn-cta">
                    <i class="fa-solid fa-plus"></i>Nueva clase
                </a>
            </div>

            <?php if (count($lessons) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Nivel</th>
                                <th>Especialidades</th>
                                <th>Días</th>
                                <th>Horario</th>
                                <th>Profesor</th>
                                <th>Capacidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lessons as $lesson): ?>
                                <?php
                                    $especialidades = explode(', ', $lesson['especialidades']);
                                    $levelClass = match (strtolower($lesson['level'])) {
                                        'principiante' => 'basico',
                                        'intermedio'   => 'intermedio',
                                        'avanzado'     => 'avanzado',
                                        default        => ''
                                    };
                                ?>
                                <tr>
                                    <td><span class="badge badge-level <?= $levelClass ?>"><?= $lesson['level'] ?></span></td>
                                    <td>
                                        <?php foreach ($especialidades as $especialidad) : ?>
                                            <span class="badge badge-specialty <?= $levelClass ?>"><?= $especialidad ?></span>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php if (!is_null($lesson['second_day_of_week'])): ?>
                                            <?= mb_substr($lesson['first_day_of_week'], 0, 3) . ', ' . mb_substr($lesson['second_day_of_week'], 0, 3) ?>
                                        <?php else: ?>
                                            <?= $lesson['first_day_of_week'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= mb_substr($lesson['start_time'], 0, 5) ?> – <?= mb_substr($lesson['end_time'], 0, 5) ?></td>
                                    <td>Prof. <?= $lesson['last_name'] ?></td>
                                    <td><?= $lesson['capacity'] ?></td>
                                    <td>
                                        <?php if ($lesson['active']): ?>
                                            <span class="badge badge-status bg-success text-white">Activa</span>
                                        <?php else : ?>
                                            <span class="badge badge-status bg-secondary text-white">Inactiva</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="#" class="action-btn btn-edit-lesson" title="Editar"
                                           data-lesson-id="<?= $lesson['register_id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="#" class="action-btn btn-view-students" title="Ver alumnos"
                                           data-lesson-id="<?= $lesson['register_id'] ?>"
                                           data-lesson-level="<?= $lesson['level'] ?>"
                                           data-lesson-specialties="<?= $lesson['especialidades'] ?>">
                                            <i class="fa-solid fa-users"></i>
                                        </a>
                                        <a href="#" class="action-btn delete btn-delete-lesson" title="Eliminar"
                                           data-lesson-id="<?= $lesson['register_id'] ?>"
                                           data-lesson-level="<?= $lesson['level'] ?>"
                                           data-lesson-specialties="<?= $lesson['especialidades'] ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else : ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-water"></i>
                    </div>
                    <h5 class="mb-2 text-navy">No se encontraron clases</h5>
                    <p class="text-muted mb-4">Probá cambiando los filtros o creá una nueva clase</p>
                    <a href="?url=new-lesson" class="btn-cta">
                        <i class="fa-solid fa-plus"></i>Crear clase
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>


<!-- MODAL: EDITAR CLASE                            -->
<div class="modal fade" id="editLessonModal" tabindex="-1" aria-labelledby="editLessonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLessonModalLabel">Editar Clase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">

                <div id="editLessonLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-2 mb-0">Cargando datos de la clase...</p>
                </div>

                <form id="formEditLesson" style="display: none;">
                    <input type="hidden" name="lesson_id" id="editLessonId">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Profesor</label>
                            <select class="form-select" name="coach_id" id="editCoachId" required>
                                <option value="" disabled>Seleccionar profesor</option>
                                <?php foreach ($coaches as $coach) : ?>
                                    <option value="<?= $coach['id'] ?>"><?= $coach['full_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nivel</label>
                            <select class="form-select" name="level" id="editLevel" required>
                                <option value="" disabled>Seleccionar nivel</option>
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
                                        <input class="form-check-input edit-specialty" type="checkbox" name="specialties[]" value="<?= $specialty['id'] ?>" id="editSpec<?= $specialty['id'] ?>">
                                        <label class="form-check-label" for="editSpec<?= $specialty['id'] ?>"><?= $specialty['specialty'] ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Primer día</label>
                            <select class="form-select" name="first_day_of_week" id="editFirstDay" required>
                                <option value="">Seleccionar día</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miercoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sabado">Sábado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Segundo día</label>
                            <select class="form-select" name="second_day_of_week" id="editSecondDay">
                                <option value="">Ninguno</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miercoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sabado">Sábado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora inicio</label>
                            <input type="time" class="form-control" name="start_time" id="editStartTime">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Hora fin</label>
                            <input type="time" class="form-control" name="end_time" id="editEndTime">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacidad</label>
                            <input type="number" class="form-control" name="capacity" id="editCapacity" min="1">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="active" id="editActive">
                                <label class="form-check-label" for="editActive">Clase activa</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-submit" id="btnSubmitEditLesson">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL: VER ALUMNOS INSCRIPTOS                  -->
<div class="modal fade" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="studentsModalLabel">Alumnos inscriptos</h5>
                    <div class="modal-subtitle" id="studentsModalSubtitle"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-0">

                <div id="studentsLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-2 mb-0">Cargando alumnos...</p>
                </div>

                <div id="studentsEmpty" class="text-center py-4" style="display: none;">
                    <i class="fa-solid fa-user-slash text-muted fa-2x mb-2"></i>
                    <p class="text-muted mb-0">No hay alumnos inscriptos en esta clase.</p>
                </div>

                <table class="table modal-table mb-0" id="studentsTable" style="display: none;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fecha de inscripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="studentsTableBody">
                    </tbody>
                </table>

            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL: CONFIRMAR ELIMINACIÓN                   -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">¿Eliminar clase?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="modal-icon">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <p class="text-center mb-1 fw-bold text-navy">Esta acción no se puede deshacer</p>
                <p class="text-center text-muted small mb-0" id="deleteModalDescription">
                    Los alumnos inscriptos en esta clase perderán su acceso. La clase será eliminada permanentemente.
                </p>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-delete-modal" id="btnConfirmDelete">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<?php include __DIR__ . '/../administrator/layout/footer.php'; ?>