<?php 
include __DIR__ . '/../users/layout/header.php'; 
/* Se declara la variable y el tipo para que intelephense no marque error de variable no definida */
/** @var string $name */
?>

  <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 900px;">

                <div class="nav-section mb-4">
                    <a href="?url=home" class="nav-btn active">
                        <i class="fa-solid fa-water"></i>Mis Clases
                    </a>
                    <a href="?url=lesson-enroll" class="nav-btn">
                        <i class="fa-solid fa-plus-circle"></i>Inscribirme
                    </a>
                    <a href="?url=lessons-history" class="nav-btn">
                        <i class="fa-solid fa-clock-rotate-left"></i>Historial
                    </a>
                </div>

                <div class="section-label">Activa</div>
                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <div class="card class-card h-100">
                            <div class="card-body">
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
                                </div>

                                <button class="btn-detail w-100">
                                    <i class="fa-solid fa-eye me-2"></i>Ver detalle
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="section-label">Cancelada</div>
                <div class="row g-4 mb-4">

                    <div class="col-md-6">
                        <div class="card class-card h-100 cancelled">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="level-icon bg-danger bg-opacity-10">
                                            <i class="fa-solid fa-water text-danger fs-5"></i>
                                        </div>
                                        <div>
                                            <span class="badge badge-status bg-danger text-white mb-1">
                                                <i class="fa-solid fa-xmark me-1"></i>Cancelada
                                            </span>
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
                                </div>

                                <button class="btn-detail w-100">
                                    <i class="fa-solid fa-eye me-2"></i>Ver detalle
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

        
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-water"></i>
                    </div>
                    <h5 class="mb-2" style="color: #1a3a5c;">Todavía no estás inscripto en ninguna clase</h5>
                    <p class="text-muted mb-4">Explorá las clases disponibles y comenzá tu camino en Alpine Natación</p>
                    <a href="#" class="btn-cta">
                        <i class="fa-solid fa-plus"></i>Ver clases disponibles
                    </a>
                </div>

                

            </div>
        </div>
    </div>

<?php include __DIR__ . '/../users/layout/footer.php'; ?>