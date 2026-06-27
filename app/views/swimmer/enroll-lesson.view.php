<?php include __DIR__ . '/../swimmer/layout/header.php';
/** @var array $lessons */

?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 900px;">
            <div class="nav-section mb-4">
                <a href="?url=home" class="nav-btn">
                    <i class="fa-solid fa-house"></i>Home
                </a>
                <a href="?url=lessons" class="nav-btn">
                    <i class="fa-solid fa-water"></i>Mis Clases
                </a>
                <a href="?url=lesson-enroll" class="nav-btn active">
                    <i class="fa-solid fa-plus-circle"></i>Inscribirme
                </a>
 
            </div>
            <?php
            $disponibles = [];
            $sinCupo = [];
            $yaInscripto = [];

            foreach ($lessons as $lesson) {
                list($enrolled_count, $max_capacity) = explode('/', $lesson['capacity']);
                $lesson['enrolled_count'] = (int)$enrolled_count;
                $lesson['max_capacity'] = (int)$max_capacity;

                if ($lesson['is_enrolled']) {
                    $yaInscripto[] = $lesson;
                } elseif ($lesson['enrolled_count'] >= $lesson['max_capacity']) {
                    $sinCupo[] = $lesson;
                } elseif ($lesson['active']) {
                    $disponibles[] = $lesson;
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

            <?php if (count($disponibles) > 0): ?>
                <div class="section-label">Disponibles</div>
                <div class="row g-4 mb-4">
                    <?php foreach ($disponibles as $lesson): ?>
                        <?php $color = getLevelColor($lesson['level']); ?>
                        <?php $especialidades = explode(', ', $lesson['especialidades']); ?>

                        <div class="col-md-6">
                            <div class="card class-card h-100">
                                <div class="card-body d-flex flex-column">
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
                                        <div class="info-item">
                                            <i class="fa-solid fa-users"></i>
                                            <span><?= $lesson['capacity'] ?> alumnos</span>
                                        </div>
                                    </div>

                                    <button class="btn-enroll w-100 mt-auto" data-bs-toggle="modal" data-bs-target="#confirmModal-<?= $lesson['register_id'] ?>">
                                        <i class="fa-solid fa-plus me-2"></i>Inscribirme
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="confirmModal-<?= $lesson['register_id'] ?>" tabindex="-1" aria-labelledby="confirmModalLabel-<?= $lesson['register_id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmModalLabel-<?= $lesson['register_id'] ?>">Confirmar inscripción</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <form method="POST"  data-form="enroll-lesson">
                                        <input type="hidden" name="lesson_id" value="<?= $lesson['register_id'] ?>">
                                        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                                        <div class="modal-body">
                                            <div class="modal-icon">
                                                <i class="fa-solid fa-water"></i>
                                            </div>
                                            <div class="modal-info">
                                                <div class="class-name"><?= htmlspecialchars($lesson['level']) ?> — <?= htmlspecialchars($lesson['especialidades']) ?></div>
                                                <div class="class-schedule"><?= formatDays($lesson) ?> · <?= formatTime($lesson['start_time']) ?> - <?= formatTime($lesson['end_time']) ?></div>
                                            </div>
                                            <p class="text-center mb-0">¿Estás seguro de que querés inscribirte en esta clase?</p>
                                        </div>
                                        <div class="modal-footer justify-content-center gap-2">
                                            <button type="button" class="btn-cancel-modal" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn-confirm-modal">Inscribirme</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (count($sinCupo) > 0): ?>
                <div class="section-label">Sin cupo</div>
                <div class="row g-4 mb-4">
                    <?php foreach ($sinCupo as $lesson): ?>
                        <?php $color = getLevelColor($lesson['level']); ?>
                        <?php $especialidades = explode(', ', $lesson['especialidades']); ?>

                        <div class="col-md-6">
                            <div class="card class-card h-100">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="level-icon bg-<?= $color['class'] ?> bg-opacity-10">
                                                <i class="fa-solid fa-water text-<?= $color['class'] ?> fs-5"></i>
                                            </div>
                                            <div>
                                                <span class="badge badge-status bg-warning text-dark mb-1">Sin cupo</span>
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
                                        <div class="info-item">
                                            <i class="fa-solid fa-users"></i>
                                            <span><?= $lesson['capacity'] ?> alumnos</span>
                                        </div>
                                    </div>

                                    <button class="btn-enroll w-100 mt-auto" disabled>
                                        <i class="fa-solid fa-ban me-2"></i>Sin cupo disponible
                                    </button>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (count($yaInscripto) > 0): ?>
                <div class="section-label">Ya inscripto</div>
                <div class="row g-4 mb-4">
                    <?php foreach ($yaInscripto as $lesson): ?>
                        <?php $especialidades = explode(', ', $lesson['especialidades']); ?>

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
                                        <div class="info-item">
                                            <i class="fa-solid fa-users"></i>
                                            <span><?= $lesson['capacity'] ?> alumnos</span>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <?php if (count($disponibles) === 0 && count($sinCupo) === 0 && count($yaInscripto) === 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-water"></i>
                    </div>
                    <h5 class="mb-2 text-navy">No hay clases disponibles</h5>
                    <p class="text-muted mb-4">No se encontraron clases activas en este momento</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../swimmer/layout/footer.php'; ?>