<?php include __DIR__ . '/../users/layout/header.php'; ?>

<div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 900px;">

                <!-- Navigation -->
                <div class="nav-section mb-4">
                    <a href="?url=home" class="nav-btn">
                        <i class="fa-solid fa-water"></i>Mis Clases
                    </a>
                    <a href="?url=lesson-enroll" class="nav-btn active">
                        <i class="fa-solid fa-plus-circle"></i>Inscribirme
                    </a>
                    <a href="?url=lessons-history" class="nav-btn">
                        <i class="fa-solid fa-clock-rotate-left"></i>Historial
                    </a>
                </div>

                <div class="section-label">Disponibles</div>
                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <div class="card class-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="level-icon bg-success bg-opacity-10">
                                            <i class="fa-solid fa-water text-success fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="badge badge-status bg-success text-white mb-1">Activa</span>
                                            <h5 class="mb-0">Nivel Intermedio</h5>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-muted small mb-3">
                                    <i class="fa-solid fa-star me-1"></i>Estilo Libre y Mariposa
                                </p>

                                <div class="d-flex flex-column gap-2 mb-3">
                                    <div class="info-item">
                                        <i class="fa-solid fa-calendar-week"></i>
                                        <span>Lunes, Miércoles, Viernes</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-clock"></i>
                                        <span>08:00 - 09:30</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-user"></i>
                                        <span>Prof. García</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-users"></i>
                                        <span>12/15 alumnos</span>
                                    </div>
                                </div>

                                <button class="btn-enroll w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#confirmModal">
                                    <i class="fa-solid fa-plus me-2"></i>Inscribirme
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card class-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="level-icon bg-primary bg-opacity-10" style="--bs-bg-opacity: 0.1;">
                                            <i class="fa-solid fa-water text-primary fs-5" style="color: #1a3a5c;"></i>
                                        </div>
                                        <div>
                                            <span class="badge badge-status bg-success text-white mb-1">Activa</span>
                                            <h5 class="mb-0">Nivel Básico</h5>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-muted small mb-3">
                                    <i class="fa-solid fa-star me-1"></i>Respiración y Flotación
                                </p>

                                <div class="d-flex flex-column gap-2 mb-3">
                                    <div class="info-item">
                                        <i class="fa-solid fa-calendar-week"></i>
                                        <span>Martes, Jueves</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-clock"></i>
                                        <span>07:00 - 08:00</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-user"></i>
                                        <span>Prof. Martínez</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-users"></i>
                                        <span>8/20 alumnos</span>
                                    </div>
                                </div>

                                <button class="btn-enroll w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#confirmModal">
                                    <i class="fa-solid fa-plus me-2"></i>Inscribirme
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="section-label">Sin cupo</div>
                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <div class="card class-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="level-icon bg-warning bg-opacity-10">
                                            <i class="fa-solid fa-water text-warning fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="badge badge-status bg-warning text-dark mb-1">Sin cupo</span>
                                            <h5 class="mb-0">Nivel Avanzado</h5>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-muted small mb-3">
                                    <i class="fa-solid fa-star me-1"></i>Espalda y Pecho
                                </p>

                                <div class="d-flex flex-column gap-2 mb-3">
                                    <div class="info-item">
                                        <i class="fa-solid fa-calendar-week"></i>
                                        <span>Sábados</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-clock"></i>
                                        <span>09:00 - 11:00</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-user"></i>
                                        <span>Prof. Sánchez</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-users"></i>
                                        <span>20/20 alumnos</span>
                                    </div>
                                </div>

                                <button class="btn-enroll w-100 mt-auto" disabled>
                                    <i class="fa-solid fa-ban me-2"></i>Sin cupo disponible
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="section-label">Ya inscripto</div>
                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <div class="card class-card enrolled h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="level-icon bg-info bg-opacity-10" style="--bs-bg-opacity: 0.1;">
                                            <i class="fa-solid fa-water fs-5" style="color: #1a3a5c;"></i>
                                        </div>
                                        <div>
                                            <span class="badge badge-status text-white mb-1" style="background-color: #1a3a5c;">Ya estás inscripto</span>
                                            <h5 class="mb-0">Nivel Intermedio</h5>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-muted small mb-3">
                                    <i class="fa-solid fa-star me-1"></i>Estilo Libre y Mariposa
                                </p>

                                <div class="d-flex flex-column gap-2 mb-3">
                                    <div class="info-item">
                                        <i class="fa-solid fa-calendar-week"></i>
                                        <span>Lunes, Miércoles, Viernes</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-clock"></i>
                                        <span>08:00 - 09:30</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-user"></i>
                                        <span>Prof. García</span>
                                    </div>
                                    <div class="info-item">
                                        <i class="fa-solid fa-users"></i>
                                        <span>12/15 alumnos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar inscripción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-icon">
                        <i class="fa-solid fa-water"></i>
                    </div>
                    <div class="modal-info">
                        <div class="class-name">Nivel Intermedio — Estilo Libre y Mariposa</div>
                        <div class="class-schedule">Lunes, Miércoles, Viernes · 08:00 - 09:30</div>
                    </div>
                    <p class="text-center mb-0">¿Estás seguro de que querés inscribirte en esta clase?</p>
                </div>
                <div class="modal-footer justify-content-center gap-2">
                    <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn-confirm-modal">Inscribirme</button>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../users/layout/footer.php'; ?>