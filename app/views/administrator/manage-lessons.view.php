<?php include __DIR__ . '/../administrator/layout/header.php';
/** @var array $lessons */
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


            <!-- <div class="filter-bar">
                <div class="row g-3">
                    <div class="col-auto">
                        <label class="form-label small text-muted mb-1">Nivel</label>
                        <select class="form-select">
                            <option>Todos</option>
                            <option>Principiante</option>
                            <option>Intermedio</option>
                            <option>Avanzado</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="form-label small text-muted mb-1">Estado</label>
                        <select class="form-select">
                            <option>Todos</option>
                            <option>Activa</option>
                            <option>Inactiva</option>
                        </select>
                    </div>
                </div>
            </div> -->


            <?php if(count($lessons) > 0) : ?>
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
                                <!-- <th>Acciones</th> -->
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($lessons as $lesson): ?>
                                <?php $especialidades = explode(', ', $lesson['especialidades']); ?>
                                <?php switch (strtolower($lesson['level'])):
                                    case 'principiante': ?>
                                        <tr>
                                            <td><span class="badge badge-level basico"><?= $lesson['level'] ?></span></td>
                                            <td>
                                                <?php foreach ($especialidades as $especialidad) : ?>
                                                    <span class="badge badge-specialty basico"><?= $especialidad ?></span>
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
                                            <?php if ($lesson['active']): ?>
                                                <td><span class="badge badge-status bg-success text-white">Activa</span></td>
                                            <?php else : ?>
                                                <td><span class="badge badge-status bg-secondary text-white">Inactiva</span></td>
                                            <?php endif; ?>


                                            <!-- <td>
                                                <a href="#" class="action-btn" title="Editar">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" class="action-btn" title="Ver alumnos" data-bs-toggle="modal" data-bs-target="#studentsModal">
                                                    <i class="fa-solid fa-users"></i>
                                                </a>
                                                <a href="#" class="action-btn delete" title="Eliminar" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td> -->


                                        </tr>

                                        <?php break; ?>
                                    <?php
                                    case 'intermedio': ?>
                                        <tr>
                                            <td><span class="badge badge-level intermedio"><?= $lesson['level'] ?></span></td>
                                            <td>
                                                <?php foreach ($especialidades as $especialidad) : ?>
                                                    <span class="badge badge-specialty intermedio"><?= $especialidad ?></span>
                                                <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <?php if (!is_null($lesson['second_day_of_week'])): ?>
                                                    <?= mb_substr($lesson['first_day_of_week'], 0, 3) . ', ' . mb_substr($lesson['second_day_of_week'], 0, 3) ?>
                                                <?php else: ?>
                                                    <?= $lesson['first_day_of_week'] ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>09:00 – 10:00</td>
                                            <td>Prof. <?= $lesson['last_name'] ?></td>
                                            <td><?= $lesson['capacity'] ?></td>
                                            <?php if ($lesson['active']): ?>
                                                <td><span class="badge badge-status bg-success text-white">Activa</span></td>
                                            <?php else : ?>
                                                <td><span class="badge badge-status bg-secondary text-white">Inactiva</span></td>
                                            <?php endif; ?>


                                            <!-- <td>
                                                <a href="#" class="action-btn" title="Editar">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" class="action-btn" title="Ver alumnos">
                                                    <i class="fa-solid fa-users"></i>
                                                </a>
                                                <a href="#" class="action-btn delete" title="Eliminar" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </td> -->


                                        </tr>
                                        <?php break; ?>
                                    <?php
                                    case 'avanzado': ?>
                                        <tr>
                                            <td><span class="badge badge-level avanzado"><?= $lesson['level'] ?></span></td>
                                            <td>
                                                <?php foreach ($especialidades as $especialidad) : ?>
                                                    <span class="badge badge-specialty avanzado"><?= $especialidad ?></span>
                                                <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <?php if (!is_null($lesson['second_day_of_week'])): ?>
                                                    <?= mb_substr($lesson['first_day_of_week'], 0, 3) . ', ' . mb_substr($lesson['second_day_of_week'], 0, 3) ?>
                                                <?php else: ?>
                                                    <?= $lesson['first_day_of_week'] ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>09:00 – 11:00</td>
                                            <td>Prof. <?= $lesson['last_name'] ?></td>
                                            <td><?= $lesson['capacity'] ?></td>

                                            <?php if($lesson['active']): ?>
                                                    <td><span class="badge badge-status bg-success text-white">Activa</span></td>
                                                <?php else : ?>
                                                    <td><span class="badge badge-status bg-secondary text-white">Inactiva</span></td>
                                                <?php endif; ?>
                                            <td>

<!-- 
                                                <a href="#" class="action-btn" title="Editar">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <a href="#" class="action-btn" title="Ver alumnos">
                                                    <i class="fa-solid fa-users"></i>
                                                </a>
                                                <a href="#" class="action-btn delete" title="Eliminar" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a> -->



                                            </td>
                                        </tr>
                                        <?php break; ?>

                                    <?php
                                    default: ?>
                                        <?php break; ?>
                                <?php endswitch; ?>
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
                <p class="text-center text-muted small mb-0">Los alumnos inscriptos en esta clase perderán su acceso. La clase será eliminada permanentemente.</p>
            </div>
            <div class="modal-footer justify-content-center gap-2">
                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-delete-modal">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentsModal" tabindex="-1" aria-labelledby="studentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="studentsModalLabel">Alumnos inscriptos</h5>
                    <div class="modal-subtitle">Nivel Básico — Respiración y Flotación</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-0">
                <table class="table modal-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Fecha de inscripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>María García</td>
                            <td>maria.garcia@email.com</td>
                            <td>15 Mar 2024</td>
                            <td><span class="badge badge-status bg-success text-white">Activo</span></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Juan Pérez</td>
                            <td>juan.perez@email.com</td>
                            <td>18 Mar 2024</td>
                            <td><span class="badge badge-status bg-success text-white">Activo</span></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Ana López</td>
                            <td>ana.lopez@email.com</td>
                            <td>22 Mar 2024</td>
                            <td><span class="badge badge-status bg-success text-white">Activo</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../administrator/layout/footer.php'; ?>