<?php
include __DIR__ . '/../../administrator/layout/header.php';
/* Se declara la variable y el tipo para que intelephense no marque error de variable no definida */
/** @var int $swimmers_data */
?>

<div class="bg-white p-5 rounded shadow-sm">
    <div class="row">
        <div class="my-2">
            <a href="?url=register-swimmer" class="btn btn-primary">Dar de alta alumno</a>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">

                <div class="card-body">
                    <h5 class="card-title">Alumnos activos</h5>
                    <p class="card-text fs-2"><?= $swimmers_data ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../../administrator/layout/footer.php'; ?>