<?php
include __DIR__ . '/../administrator/layout/header.php'; 
/* Se declara la variable y el tipo para que intelephense no marque error de variable no definida */
/** @var string $name */
/** @var int $active_alumns */
/** @var int $active_coaches */
/** @var array $activity_log */
/** @var int $total_users */
/** */

function timeAgo(string $ts)
{
    date_default_timezone_set('America/Argentina/Buenos_Aires');
    $diff = time() - strtotime($ts);


    return match (true) {
        $diff < 60 => 'hace ' . $diff . 's',
        $diff < 3600 => 'hace ' . floor($diff / 60) . 'm',
        $diff < 86400 => 'hace ' . floor($diff / 3600) . 'hs',
        default => date('d/m H:i', strtotime($ts))
    };
}
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
                            <span class="text-decoration-none">
                                <div class="d-flex align-items-center p-2 rounded stat-card mb-2">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="fa-solid fa-users text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-primary text-start"><?= $total_users ?></h4>
                                        <small class="text-muted">Total Usuarios</small>
                                    </div>
                                </div>
                            </span>
                            <a href="?url=swimmers" class="text-decoration-none">
                                <div class="d-flex align-items-center p-2 rounded stat-card mb-2">
                                    <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                        <i class="fa-solid fa-graduation-cap text-success"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 text-success text-start"><?= $active_alumns ?></h4>
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
                                        <h4 class="mb-0 text-info text-start"><?= $active_coaches ?></h4>
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
                                <a href="?url=new-lesson" class="btn btn-outline-warning btn-sm quick-btn">
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
                                        <?php foreach ($activity_log as $log): ?>
                                            <tr class="activity-item">
                                                <td class="ps-2 ps-md-3">
                                                    <?php switch ($log['type']):
                                                        case 'coach_registered': ?>
                                                            <span class="badge bg-success bg-opacity-10 text-success p-1 p-md-2 rounded-circle me-2">
                                                                <i class="fa-solid fa-user-plus"></i>
                                                            </span>
                                                            <small>Se dió de alta el correo <strong><?= htmlspecialchars($log['subject']) ?></strong> con perfil de <strong>Profesor</strong></small>
                                                            <?php break; ?>
                                                        <?php
                                                        case 'swimmer_registered': ?>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary p-1 p-md-2 rounded-circle me-2">
                                                                <i class="fa-solid fa-user-plus"></i>
                                                            </span>
                                                            <small>Se dió de alta el correo <strong><?= htmlspecialchars($log['subject']) ?></strong> con perfil de <strong>Alumno</strong></small>
                                                            <?php break; ?>
                                                        <?php
                                                        case 'profile_completed': ?>
                                                            <span class="badge bg-warning bg-opacity-10 text-warning p-1 p-md-2 rounded-circle me-2">
                                                                <i class="fa-solid fa-clipboard-check"></i>
                                                            </span>
                                                            <small><strong><?= htmlspecialchars($log['subject']) ?></strong> completó el registro de su perfil</small>
                                                            <?php break; ?>
                                                        <?php
                                                        case 'class_created': ?>
                                                            <span class="badge bg-info bg-opacity-10 text-info p-1 p-md-2 rounded-circle me-2">
                                                                <i class="fa-solid fa-water-ladder"></i>
                                                            </span>
                                                            <small>Se creó una nueva clase de nivel <strong><?= htmlspecialchars($log['subject']) ?></strong></small>
                                                            <?php break; ?>
                                                        <?php
                                                        case 'class_deleted': ?>
                                                            <span class="badge bg-danger bg-opacity-10 text-danger p-1 p-md-2 rounded-circle me-2">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </span>
                                                            <small>Se eliminó la clase de nivel <strong><?= htmlspecialchars($log['subject']) ?></strong></small>
                                                            <?php break; ?>
                                                        <?php
                                                        case 'swimmer_enrolled': ?>
                                                            <?php [$name, $className] = explode('|', $log['subject']); ?>
                                                            <span class="badge bg-success bg-opacity-10 text-success p-1 p-md-2 rounded-circle me-2">
                                                                <i class="fa-solid fa-person-swimming"></i>
                                                            </span>
                                                            <small><strong><?= htmlspecialchars($name) ?></strong> se inscribió a la clase <strong><?= htmlspecialchars($className) ?></strong></small>
                                                            <?php break; ?>
                                                        <?php
                                                        case 'swimmer_self_registered': ?>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary p-1 p-md-2 rounded-circle me-2">
                                                                <i class="fa-solid fa-user-plus"></i>
                                                            </span>
                                                            <small>Se registró el usuario <strong><?= htmlspecialchars($log['subject']) ?></strong> con perfil de <strong>Alumno</strong></small>
                                                            <?php break; ?>
                                                    <?php endswitch; ?>
                                                </td>
                                                <td class="text-end pe-2 pe-md-3"><small class="text-muted"><?= timeAgo($log['ts']) ?></small></td>
                                            </tr>
                                        <?php endforeach; ?>

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