<?php 
include __DIR__ . '/users/layout/header.php'; 
/* Se declara la variable y el tipo para que intelephense no marque error de variable no definida */
/** @var string $name */
?>
<div class="bg-white p-5 rounded shadow-sm">
    <h1>Bienvenido, <?= htmlspecialchars($name) ?><h1>
    <p class="lead">Aquí vas a poder ver e inscribirte a tus clases.</p>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Clases activas</h5>
                    <p class="card-text fs-2">...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/users/layout/footer.php'; ?>