<?php
include __DIR__ . '/../coach/layout/header.php';
/** @var array $lessons */
$currentUrl = $_GET['url'] ?? 'coach-lessons';

$activas = [];
$inactivas = [];

foreach ($lessons as $lesson) {
    if ($lesson['active']) {
        $activas[] = $lesson;
    } else {
        $inactivas[] = $lesson;
    }
}

function getLevelColorCL($level) {
    return match(strtolower($level)) {
        'principiante' => ['class' => 'success', 'hex' => '#198754'],
        'intermedio'   => ['class' => 'navy',    'hex' => '#1a3a5c'],
        'avanzado'     => ['class' => 'purple',  'hex' => '#6610f2'],
        default        => ['class' => 'secondary','hex' => '#6c757d'],
    };
}

function formatTimeCL($time) {
    return mb_substr($time, 0, 5);
}

function formatDaysCL($lesson) {
    if (!is_null($lesson['second_day_of_week'])) {
        return $lesson['first_day_of_week'] . ', ' . $lesson['second_day_of_week'];
    }
    return $lesson['first_day_of_week'];
}
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 900px;">

            <div class="nav-section mb-4">
                <a href="?url=home" class="nav-btn <?= $currentUrl === 'home' ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i>Home
                </a>
                <a href="?url=coach-lessons" class="nav-btn <?= $currentUrl === 'coach-lessons' ? 'active' : '' ?>">
                    <i class="fa-solid fa-water"></i>Mis Clases
                </a>

            </div>

            <?php if (count($activas) > 0): ?>
                <div class="section-label">Clases activas</div>
                <div class="row g-4 mb-4">
                    <?php foreach ($activas as $lesson): ?>
                        <?php $color = getLevelColorCL($lesson['level']); ?>
                        <?php $especialidades = explode(', ', $lesson['especialidades']); ?>
                        <?php $porcentaje = $lesson['capacity'] > 0 ? round(($lesson['total_alumnos'] / $lesson['capacity']) * 100) : 0; ?>

                        <div class="col-md-6">
                            <div class="card class-card h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="level-icon" style="background: <?= $color['hex'] ?>15;">
                                                <i class="fa-solid fa-water fs-5" style="color: <?= $color['hex'] ?>;"></i>
                                            </div>
                                            <div>
                                                <span class="badge badge-status bg-success text-white mb-1">Activa</span>
                                                <h5 class="mb-0"><?= htmlspecialchars($lesson['level']) ?></h5>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-3">
                                        <?php foreach ($especialidades as $especialidad): ?>
                                            <i class="fa-solid fa-star me-1"></i><?= htmlspecialchars($especialidad) ?>
                                        <?php endforeach; ?>
                                    </p>

                                    <div class="d-flex flex-column gap-2 mb-3">
                                        <div class="info-item">
                                            <i class="fa-solid fa-calendar-week"></i>
                                            <span><?= formatDaysCL($lesson) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fa-solid fa-clock"></i>
                                            <span><?= formatTimeCL($lesson['start_time']) ?> - <?= formatTimeCL($lesson['end_time']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fa-solid fa-users"></i>
                                            <span><?= $lesson['total_alumnos'] ?>/<?= $lesson['capacity'] ?> alumnos</span>
                                        </div>
                                    </div>

                                    <div class="capacity-bar-wrapper mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted">Capacidad</small>
                                            <small class="text-muted"><?= $porcentaje ?>%</small>
                                        </div>
                                        <div class="capacity-bar">
                                            <div class="capacity-bar-fill" style="width: <?= $porcentaje ?>%; background: <?= $porcentaje >= 90 ? '#dc3545' : ($porcentaje >= 70 ? '#ffc107' : $color['hex']) ?>;"></div>
                                        </div>
                                    </div>

                                    <button 
                                        class="btn-detail w-100 mt-auto" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#studentsModal-<?= $lesson['register_id'] ?>"
                                        data-lesson-id="<?= $lesson['register_id'] ?>"
                                    >
                                        <i class="fa-solid fa-users me-2"></i>Ver alumnos
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="studentsModal-<?= $lesson['register_id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <?= htmlspecialchars($lesson['level']) ?> — <?= htmlspecialchars($lesson['especialidades']) ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex align-items-center gap-2 text-muted small mb-3">
                                            <i class="fa-solid fa-calendar-week"></i>
                                            <span><?= formatDaysCL($lesson) ?> · <?= formatTimeCL($lesson['start_time']) ?> - <?= formatTimeCL($lesson['end_time']) ?></span>
                                            <span class="ms-auto"><?= $lesson['total_alumnos'] ?>/<?= $lesson['capacity'] ?> alumnos</span>
                                        </div>

                                        <?php if ($lesson['total_alumnos'] > 0): ?>
                                            <div class="students-list" id="studentsList-<?= $lesson['register_id'] ?>">
                                                <div class="text-center py-3">
                                                    <div class="spinner-border spinner-border-sm text-muted" role="status"></div>
                                                    <small class="text-muted ms-2">Cargando alumnos...</small>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center py-4">
                                                <i class="fa-solid fa-users text-muted mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">No hay alumnos inscriptos en esta clase</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (count($inactivas) > 0): ?>
                <div class="section-label">Inactivas</div>
                <div class="row g-4 mb-4">
                    <?php foreach ($inactivas as $lesson): ?>
                        <?php $especialidades = explode(', ', $lesson['especialidades']); ?>

                        <div class="col-md-6">
                            <div class="card class-card h-100 cancelled">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="level-icon bg-danger bg-opacity-10">
                                                <i class="fa-solid fa-water text-danger fs-5"></i>
                                            </div>
                                            <div>
                                                <span class="badge badge-status bg-secondary text-white mb-1">Inactiva</span>
                                                <h5 class="mb-0"><?= htmlspecialchars($lesson['level']) ?></h5>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-muted small mb-3">
                                        <?php foreach ($especialidades as $especialidad): ?>
                                            <i class="fa-solid fa-star me-1"></i><?= htmlspecialchars($especialidad) ?>
                                        <?php endforeach; ?>
                                    </p>

                                    <div class="d-flex flex-column gap-2 mb-3">
                                        <div class="info-item">
                                            <i class="fa-solid fa-calendar-week"></i>
                                            <span><?= formatDaysCL($lesson) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fa-solid fa-clock"></i>
                                            <span><?= formatTimeCL($lesson['start_time']) ?> - <?= formatTimeCL($lesson['end_time']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fa-solid fa-users"></i>
                                            <span><?= $lesson['total_alumnos'] ?>/<?= $lesson['capacity'] ?> alumnos</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (count($activas) === 0 && count($inactivas) === 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-whistle"></i>
                    </div>
                    <h5 class="mb-2" style="color: #1a3a5c;">No tenés clases asignadas</h5>
                    <p class="text-muted mb-0">Cuando el administrador te asigne una clase, va a aparecer acá</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../coach/layout/footer.php'; ?>