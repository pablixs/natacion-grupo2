<?php 
include __DIR__ . '/../administrator/layout/header.php'; 
/* Se declara la variable y el tipo para que intelephense no marque error de variable no definida */
/** @var string $name */
/** @var int $active_alumns */
/** @var int $active_coaches */
/** @var int $total_users */
?>

<div class="p-3 p-md-4 p-lg-5 rounded ">
    <div class="row justify-content-center mx-lg-5 py-4 px-lg-4">
        <div class="col-12">
            
            <h4 class="mb-3 mb-md-4">Bienvenido, <?= htmlspecialchars($name) ?></h4>

            <div class="row g-3 g-md-4">
                <div class="col-12 col-lg-4 order-2 order-lg-1">
                    <div class="card border-0 shadow-sm mb-3 mb-lg-4">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Resumen</h6>
                            <a href="?url=?" class="text-decoration-none">
                                <div class="d-flex align-items-center p-2 rounded stat-card mb-2">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="fa-solid fa-users text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-primary"><?= $total_users ?></h4>
                                        <small class="text-muted">Total Usuarios</small>
                                    </div>
                                </div>
                            </a>
                            <a href="?url=swimmers" class="text-decoration-none">
                                <div class="d-flex align-items-center p-2 rounded stat-card mb-2">
                                    <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                        <i class="fa-solid fa-graduation-cap text-success"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-success"><?= $active_alumns ?></h4>
                                        <small class="text-muted">Alumnos</small>
                                    </div>
                                </div>
                            </a>
                            <a href="?url=coaches" class="text-decoration-none">
                                <div class="d-flex align-items-center p-2 rounded stat-card">
                                    <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                        <i class="fa-solid fa-chalkboard-user text-info"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-info"><?= $active_coaches ?></h4>
                                        <small class="text-muted">Profesores</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Acciones Rápidas</h6>
                            <div class="d-grid gap-2">
                                <a href="?url=register-swimmer" class="btn btn-outline-primary btn-sm quick-btn">
                                    <i class="fa-solid fa-user-plus me-2"></i>
                                    Registrar Alumno
                                </a>
                                <a href="?url=register-coach" class="btn btn-outline-success btn-sm quick-btn">
                                    <i class="fa-solid fa-person-chalkboard me-2"></i>
                                    Registrar Profesor
                                </a>
                                <a href="#" class="btn btn-outline-warning btn-sm quick-btn">
                                    <i class="fa-solid fa-calendar-plus me-2"></i>
                                    Nueva Clase
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8 order-1 order-lg-2">
                    <div class="card border-0 shadow-sm mb-3 mb-lg-4">
                        <div class="card-header bg-white py-2 py-md-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                            <h6 class="mb-0"><i class="fa-solid fa-clock-rotate-left me-2 text-muted"></i>Actividad Reciente</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0">
                                    <tbody>
                                        <tr class="activity-item">
                                            <td class="ps-2 ps-md-3">
                                                <span class="badge bg-success bg-opacity-10 text-success p-1 p-md-2 rounded-circle me-2">
                                                    <i class="fa-solid fa-user-plus"></i>
                                                </span>
                                                <small><strong>María García</strong> se inscribió en <strong>Nivel Inicial</strong></small>
                                            </td>
                                            <td class="text-end pe-2 pe-md-3"><small class="text-muted">31/05 14:30</small></td>
                                        </tr>
                                        <tr class="activity-item">
                                            <td class="ps-2 ps-md-3">
                                                <span class="badge bg-primary bg-opacity-10 text-primary p-1 p-md-2 rounded-circle me-2">
                                                    <i class="fa-solid fa-user-plus"></i>
                                                </span>
                                                <small>Se dio de alta a <strong>Carlos López</strong> como alumno</small>
                                            </td>
                                            <td class="text-end pe-2 pe-md-3"><small class="text-muted">31/05 13:45</small></td>
                                        </tr>
                                        <tr class="activity-item">
                                            <td class="ps-2 ps-md-3">
                                                <span class="badge bg-warning bg-opacity-10 text-warning p-1 p-md-2 rounded-circle me-2">
                                                    <i class="fa-solid fa-clipboard-check"></i>
                                                </span>
                                                <small><strong>Ana Martínez</strong> completó su registro</small>
                                            </td>
                                            <td class="text-end pe-2 pe-md-3"><small class="text-muted">31/05 11:20</small></td>
                                        </tr>
                                        <tr class="activity-item">
                                            <td class="ps-2 ps-md-3">
                                                <span class="badge bg-info bg-opacity-10 text-info p-1 p-md-2 rounded-circle me-2">
                                                    <i class="fa-solid fa-chalkboard-user"></i>
                                                </span>
                                                <small>Se dio de alta a <strong>Juan Pérez</strong> como profesor</small>
                                            </td>
                                            <td class="text-end pe-2 pe-md-3"><small class="text-muted">30/05 16:00</small></td>
                                        </tr>
                                        <tr class="activity-item">
                                            <td class="ps-2 ps-md-3">
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary p-1 p-md-2 rounded-circle me-2">
                                                    <i class="fa-solid fa-calendar-plus"></i>
                                                </span>
                                                <small>Se creó la clase <strong>Nivel Intermedio</strong></small>
                                            </td>
                                            <td class="text-end pe-2 pe-md-3"><small class="text-muted">30/05 09:15</small></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-2 py-md-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                            <h6 class="mb-0">Próximas Clases</h6>
                            <a href="#" class="text-decoration-none text-primary small">Ver todas <i class="fa-solid fa-arrow-right ms-1"></i></a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 ps-2 ps-md-3">Fecha</th>
                                            <th class="border-0">Hora</th>
                                            <th class="border-0">Clase</th>
                                            <th class="border-0 d-none d-md-table-cell">Profesor</th>
                                            <th class="border-0">Alum.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="class-item">
                                            <td class="ps-2 ps-md-3"><small>31/05</small></td>
                                            <td><span class="badge bg-dark">08:00</span></td>
                                            <td><strong>Nivel Inicial</strong></td>
                                            <td class="d-none d-md-table-cell">Prof. García</td>
                                            <td><span class="badge bg-dark rounded-pill">12</span></td>
                                        </tr>
                                        <tr class="class-item">
                                            <td class="ps-2 ps-md-3"><small>31/05</small></td>
                                            <td><span class="badge bg-dark">10:00</span></td>
                                            <td><strong>Nivel Intermedio</strong></td>
                                            <td class="d-none d-md-table-cell">Prof. López</td>
                                            <td><span class="badge bg-dark rounded-pill">8</span></td>
                                        </tr>
                                        <tr class="class-item">
                                            <td class="ps-2 ps-md-3"><small>31/05</small></td>
                                            <td><span class="badge bg-dark">14:00</span></td>
                                            <td><strong>Nivel Avanzado</strong></td>
                                            <td class="d-none d-md-table-cell">Prof. Martínez</td>
                                            <td><span class="badge bg-dark rounded-pill">10</span></td>
                                        </tr>
                                        <tr class="class-item">
                                            <td class="ps-2 ps-md-3"><small>31/05</small></td>
                                            <td><span class="badge bg-dark">16:00</span></td>
                                            <td><strong>Competencia</strong></td>
                                            <td class="d-none d-md-table-cell">Prof. Sánchez</td>
                                            <td><span class="badge bg-dark rounded-pill">6</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../administrator/layout/footer.php'; ?>