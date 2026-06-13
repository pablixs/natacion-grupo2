<?php
include __DIR__ . '/../coach/layout/header.php';
/** @var array $lessons */
/** @var string $name */
$currentUrl = $_GET['url'] ?? 'coach-home';

$activas = array_filter($lessons, fn($l) => $l['active']);
$totalActivas = count($activas);

// Total alumnos en todas las clases
$totalAlumnos = 0;
foreach ($activas as $l) {
    $totalAlumnos += (int)$l['total_alumnos'];
}

// Días únicos de la semana
$diasUnicos = [];
foreach ($activas as $l) {
    $diasUnicos[$l['first_day_of_week']] = true;
    if (!is_null($l['second_day_of_week'])) {
        $diasUnicos[$l['second_day_of_week']] = true;
    }
}

// Próxima clase
$diasMap = [
    'Lunes' => 1, 'Martes' => 2, 'Miércoles' => 3,
    'Jueves' => 4, 'Viernes' => 5, 'Sábado' => 6, 'Domingo' => 7
];

$hoy = (int)date('N');
$horaActual = date('H:i');
$proximaClase = null;
$menorDiff = PHP_INT_MAX;

foreach ($activas as $lesson) {
    $dias = [$lesson['first_day_of_week']];
    if (!is_null($lesson['second_day_of_week'])) {
        $dias[] = $lesson['second_day_of_week'];
    }

    foreach ($dias as $dia) {
        if (!isset($diasMap[$dia])) continue;
        $numDia = $diasMap[$dia];
        $diff = $numDia - $hoy;

        if ($diff < 0) $diff += 7;
        if ($diff === 0 && mb_substr($lesson['start_time'], 0, 5) <= $horaActual) $diff = 7;

        if ($diff < $menorDiff) {
            $menorDiff = $diff;
            $proximaClase = $lesson;
            $proximaClase['dia_proximo'] = $dia;
        }
    }
}

function getLevelColorCoach($level) {
    return match(strtolower($level)) {
        'principiante' => ['class' => 'success', 'hex' => '#198754'],
        'intermedio'   => ['class' => 'navy',    'hex' => '#1a3a5c'],
        'avanzado'     => ['class' => 'purple',  'hex' => '#6610f2'],
        default        => ['class' => 'secondary','hex' => '#6c757d'],
    };
}

function formatTimeCoach($time) {
    return mb_substr($time, 0, 5);
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

            <div class="greeting-card mb-4">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <h4 class="mb-1">¡Hola, Prof. <?= htmlspecialchars($name) ?>!</h4>
                        <p class="mb-0 text-muted">
                            <?php if ($totalActivas > 0): ?>
                                Tenés <?= $totalActivas ?> clase<?= $totalActivas > 1 ? 's' : '' ?> activa<?= $totalActivas > 1 ? 's' : '' ?> con <?= $totalAlumnos ?> alumno<?= $totalAlumnos !== 1 ? 's' : '' ?> en total
                            <?php else: ?>
                                No tenés clases asignadas por el momento
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(26,58,92,0.08);">
                            <i class="fa-solid fa-water" style="color: #1a3a5c;"></i>
                        </div>
                        <div class="stat-number"><?= $totalActivas ?></div>
                        <div class="stat-label">Clases activas</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(25,135,84,0.08);">
                            <i class="fa-solid fa-users" style="color: #198754;"></i>
                        </div>
                        <div class="stat-number"><?= $totalAlumnos ?></div>
                        <div class="stat-label">Alumnos</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(102,16,242,0.08);">
                            <i class="fa-solid fa-calendar-check" style="color: #6610f2;"></i>
                        </div>
                        <div class="stat-number"><?= count($diasUnicos) ?></div>
                        <div class="stat-label">Días por semana</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(220,53,69,0.08);">
                            <i class="fa-solid fa-star" style="color: #dc3545;"></i>
                        </div>
                        <div class="stat-number">
                            <?php
                                $especialidades = [];
                                foreach ($activas as $l) {
                                    foreach (explode(', ', $l['especialidades']) as $esp) {
                                        $especialidades[$esp] = true;
                                    }
                                }
                                echo count($especialidades);
                            ?>
                        </div>
                        <div class="stat-label">Especialidades</div>
                    </div>
                </div>
            </div>

            <?php if ($proximaClase): ?>
                <?php $color = getLevelColorCoach($proximaClase['level']); ?>
                <div class="section-label">Próxima clase</div>
                <div class="next-class-card mb-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="next-class-icon" style="background: <?= $color['hex'] ?>15;">
                            <i class="fa-solid fa-water" style="color: <?= $color['hex'] ?>;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-0"><?= htmlspecialchars($proximaClase['level']) ?></h5>
                            <span class="text-muted small"><?= htmlspecialchars($proximaClase['especialidades']) ?></span>
                        </div>
                        <div class="next-class-day text-end">
                            <span class="next-class-day-label"><?= $proximaClase['dia_proximo'] ?></span>
                            <span class="next-class-time"><?= formatTimeCoach($proximaClase['start_time']) ?> - <?= formatTimeCoach($proximaClase['end_time']) ?></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2 text-muted small">
                            <i class="fa-solid fa-users"></i>
                            <span><?= $proximaClase['total_alumnos'] ?>/<?= $proximaClase['capacity'] ?> alumnos</span>
                        </div>

                    </div>
                </div>
            <?php endif; ?>

            <?php if ($totalActivas > 0): ?>
                <div class="section-label">Resumen de clases</div>
                <div class="classes-summary mb-4">
                    <?php foreach ($activas as $lesson): ?>
                        <?php $color = getLevelColorCoach($lesson['level']); ?>
                        <div class="summary-row">
                            <div class="d-flex align-items-center gap-3">
                                <div class="summary-dot" style="background: <?= $color['hex'] ?>;"></div>
                                <div>
                                    <span class="summary-level"><?= htmlspecialchars($lesson['level']) ?></span>
                                    <span class="summary-specs text-muted"> — <?= htmlspecialchars($lesson['especialidades']) ?></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="summary-students text-muted">
                                    <i class="fa-solid fa-users me-1"></i><?= $lesson['total_alumnos'] ?>/<?= $lesson['capacity'] ?>
                                </span>
                                <span class="summary-schedule text-muted">
                                    <?php
                                        $dias = $lesson['first_day_of_week'];
                                        if (!is_null($lesson['second_day_of_week'])) $dias .= ', ' . $lesson['second_day_of_week'];
                                        echo $dias . ' · ' . formatTimeCoach($lesson['start_time']) . ' - ' . formatTimeCoach($lesson['end_time']);
                                    ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
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