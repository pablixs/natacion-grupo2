<?php include __DIR__ . '/../users/layout/header.php'; ?>


<div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12" style="max-width: 900px;">

                <div class="nav-section mb-4">
                    <a href="?url=home" class="nav-btn">
                        <i class="fa-solid fa-water"></i>Mis Clases
                    </a>
                    <a href="?url=lesson-enroll" class="nav-btn">
                        <i class="fa-solid fa-plus-circle"></i>Inscribirme
                    </a>
                    <a href="?url=lessons-history" class="nav-btn active">
                        <i class="fa-solid fa-clock-rotate-left"></i>Historial
                    </a>
                </div>

                <div class="timeline">

                    <div class="timeline-item activa">
                        <div class="timeline-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="level-title">Nivel Avanzado</div>
                                    <div class="specialty">Espalda y Pecho</div>
                                </div>
                                <span class="badge-reason text-white" style="background-color: #198754;">
                                    <i class="fa-solid fa-circle me-1"></i>Activa
                                </span>
                            </div>
                            <div class="period">
                                <i class="fa-solid fa-calendar me-1"></i>Dic 2024 – Mar 2025
                            </div>
                            <div class="professor mt-1">
                                <i class="fa-solid fa-user me-1"></i>Prof. Sánchez
                            </div>
                        </div>
                    </div>

                    <div class="timeline-item inactiva">
                        <div class="timeline-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="level-title">Nivel Intermedio</div>
                                    <div class="specialty">Estilo Libre y Mariposa</div>
                                </div>
                                <span class="badge-reason text-white" style="background-color: #6c757d;">
                                    <i class="fa-solid fa-circle me-1"></i>Inactiva
                                </span>
                            </div>
                            <div class="period">
                                <i class="fa-solid fa-calendar me-1"></i>Ago 2024 – Nov 2024
                            </div>
                            <div class="professor mt-1">
                                <i class="fa-solid fa-user me-1"></i>Prof. García
                            </div>
                        </div>
                    </div>

                </div>

           
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <h5 class="mb-2" style="color: #1a3a5c;">Todavía no tenés clases en tu historial</h5>
                    <p class="text-muted mb-4">Cuando completes o abandones clases, aparecerán acá</p>
                    <a href="#" class="btn-cta">
                        <i class="fa-solid fa-plus"></i>Ver clases disponibles
                    </a>
                </div>

         

            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../users/layout/footer.php'; ?>