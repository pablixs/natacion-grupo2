<?php
include __DIR__ . '/../swimmer/layout/header.php';
/** @var array $lessons */
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 900px;">

            <div class="nav-section mb-4">
                <a href="?url=home" class="nav-btn ">
                    <i class="fa-solid fa-house"></i>Home
                </a>
                <a href="?url=lessons" class="nav-btn active">
                    <i class="fa-solid fa-water"></i>Mis Clases
                </a>
                <a href="?url=lesson-enroll" class="nav-btn">
                    <i class="fa-solid fa-plus-circle"></i>Inscribirme
                </a>

            </div>

            <?php
            $activas = [];
            $canceladas = [];

            foreach ($lessons as $lesson) {
                if ($lesson['active']) {
                    $activas[] = $lesson;
                } else {
                    $canceladas[] = $lesson;
                }
            }

            function getLevelColor($level)
            {
                return match (strtolower($level)) {
                    'principiante' => ['class' => 'primary', 'hex' => '#0d6efd'],
                    'intermedio'   => ['class' => 'success', 'hex' => '#198754'],
                    'avanzado'     => ['class' => 'warning', 'hex' => '#ffc107'],
                    default        => ['class' => 'secondary', 'hex' => '#6c757d'],
                };
            }

            function formatDays($lesson)
            {
                if (!is_null($lesson['second_day_of_week'])) {
                    return $lesson['first_day_of_week'] . ', ' . $lesson['second_day_of_week'];
                }
                return $lesson['first_day_of_week'];
            }

            function formatTime($time)
            {
                return mb_substr($time, 0, 5);
            }
            ?>

            <?php if (count($activas) > 0): ?>
                <div class="section-label">Activa</div>
                <div class="row g-4 mb-4">
                    <?php foreach ($activas as $lesson): ?>
                        <?php $color = getLevelColor($lesson['level']); ?>
                        <?php $especialidades = explode(', ', $lesson['especialidades']); ?>

                        <div class="col-md-6">
                            <div class="card class-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="level-icon bg-<?= $color['class'] ?> bg-opacity-10">
                                                <i class="fa-solid fa-water text-<?= $color['class'] ?> fs-5"></i>
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
                                            <span><?= formatDays($lesson) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fa-solid fa-clock"></i>
                                            <span><?= formatTime($lesson['start_time']) ?> - <?= formatTime($lesson['end_time']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fa-solid fa-user"></i>
                                            <span>Prof. <?= htmlspecialchars($lesson['last_name']) ?></span>
                                        </div>
                                    </div>

                                    <button class="btn-unenroll w-100" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $lesson['register_id'] ?>">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i>Dar de baja
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="detailModal-<?= $lesson['register_id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel-<?= $lesson['register_id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel-<?= $lesson['register_id'] ?>">Detalle de clase</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <form method="POST" action="" data-form="unenroll-lesson">
                                        <input type="hidden" name="lesson_id" value="<?= $lesson['register_id'] ?>">
                                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                                        <div class="modal-body">
                                            <div class="modal-info">
                                                <div class="class-name"><?= htmlspecialchars($lesson['level']) ?> — <?= htmlspecialchars($lesson['especialidades']) ?></div>
                                                <div class="class-schedule"><?= formatDays($lesson) ?> · <?= formatTime($lesson['start_time']) ?> - <?= formatTime($lesson['end_time']) ?></div>
                                                <div class="class-schedule">Prof. <?= htmlspecialchars($lesson['last_name']) ?></div>
                                            </div>
                                            <p class="text-center mb-0">¿Estás seguro de que querés darte de baja de esta clase?</p>
                                        </div>
                                        <div class="modal-footer justify-content-center gap-2">
                                            <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn-confirm-modal btn-danger-modal">
                                                <i class="fa-solid fa-right-from-bracket me-1"></i>Dar de baja
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (count($canceladas) > 0): ?>
                <div class="section-label">Cancelada</div>
                <div class="row g-4 mb-4">
                    <?php foreach ($canceladas as $lesson): ?>
                        <?php $especialidades = explode(', ', $lesson['especialidades']); ?>

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
                                            <span><?= formatDays($lesson) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fa-solid fa-clock"></i>
                                            <span><?= formatTime($lesson['start_time']) ?> - <?= formatTime($lesson['end_time']) ?></span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fa-solid fa-user"></i>
                                            <span>Prof. <?= htmlspecialchars($lesson['last_name']) ?></span>
                                        </div>
                                    </div>

                                    <button class="btn-detail w-100" data-bs-toggle="modal" data-bs-target="#detailModal-<?= $lesson['register_id'] ?>">
                                        <i class="fa-solid fa-eye me-2"></i>Ver detalle
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="detailModal-<?= $lesson['register_id'] ?>" tabindex="-1" aria-labelledby="detailModalLabel-<?= $lesson['register_id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailModalLabel-<?= $lesson['register_id'] ?>">Detalle de clase</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="modal-icon">
                                            <i class="fa-solid fa-water"></i>
                                        </div>
                                        <div class="modal-info">
                                            <div class="class-name"><?= htmlspecialchars($lesson['level']) ?> — <?= htmlspecialchars($lesson['especialidades']) ?></div>
                                            <div class="class-schedule"><?= formatDays($lesson) ?> · <?= formatTime($lesson['start_time']) ?> - <?= formatTime($lesson['end_time']) ?></div>
                                            <div class="class-schedule">Prof. <?= htmlspecialchars($lesson['last_name']) ?></div>
                                        </div>
                                        <p class="text-center text-danger mb-0">
                                            <i class="fa-solid fa-circle-xmark me-1"></i>Esta clase fue cancelada
                                        </p>
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

            <?php if (count($activas) === 0 && count($canceladas) === 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-water"></i>
                    </div>
                    <h5 class="mb-2" style="color: #1a3a5c;">Todavía no estás inscripto en ninguna clase</h5>
                    <p class="text-muted mb-4">Explorá las clases disponibles</p>
                    <a href="?url=lesson-enroll" class="btn-cta">
                        <i class="fa-solid fa-plus"></i>Ver clases disponibles
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../swimmer/layout/footer.php'; ?>