<?php 
include __DIR__ . '/../administrator/layout/header.php'; 
/* Se declara la variable y el tipo para que intelephense no marque error de variable no definida */
/** @var string $name */
/** @var int $active_alumns */
/** @var int $active_coaches */
/** @var int $total_users */
?>

<div class="bg-white p-5 rounded shadow-sm">
    <h1>Bienvenido, <?= htmlspecialchars($name) ?><h1>
    <p class="lead">Este es el panel administrativo de la escuela.</p>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <a href="?url=home"  class="card text-decoration-none text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Alumnos</h5>
                    <p class="card-text fs-2">Activos: <?=  $active_alumns ?></p>
                </div>
            </a>
        </div>

        <a href="#" class="col-md-4">
            <div class="card text-decoration-none text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Profesores</h5>
                    <p class="card-text fs-2">Activos: <?=  $active_coaches ?></p>
                </div>
            </div>
        </a>
        <a href="#" class="col-md-4">
            <div class="card text-decoration-none text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Usuarios</h5>
                    <p class="card-text fs-2">Total: <?=  $total_users ?></p>
                </div>
            </div>
        </a>
    </div>
</div>

<?php include __DIR__ . '/../administrator/layout/footer.php'; ?>