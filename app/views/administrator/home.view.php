<?php 
include __DIR__ . '/../users/layout/header.php'; 
/* Se declara la variable y el tipo para que intelephense no marque error de variable no definida */
/** @var string $name */
/** @var string $active_alumns */
/** @var string $total_users */
?>

<div class="bg-white p-5 rounded shadow-sm">
    <h1>Bienvenido, <?= htmlspecialchars($name) ?><h1>
    <p class="lead">Este es el panel administrativo de la escuela.</p>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Alumnos activos</h5>
                    <p class="card-text fs-2"><?=  $active_alumns ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Usuarios totales</h5>
                    <p class="card-text fs-2"><?=  $total_users ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../users/layout/footer.php'; ?>