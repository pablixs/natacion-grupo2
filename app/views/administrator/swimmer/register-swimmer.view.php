<?php
include __DIR__ . '/../../administrator/layout/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center"><?php echo $title ?? 'Registro de Swimmer'; ?></h4>
                </div>
                <div class="card-body">
                    <form id="formRegisterSwimmer" action="?url=create-swimmer" method="POST" enctype="multipart/form-data">
                        <div class="row row-cols-1 row-cols-md-2">
                            <div class="col mb-3 w-100">
                                <p>Para dar de alta un alumno debes ingresar su correo. Se le asignará un usuario y luego el alumno deberá completar el registro manualmente.</p>
                            </div>
                            <hr class="w-100">

                            <div class="col mb-3 w-100">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder="juan@correo.com"
                                    required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary px-5">Registrar alumno</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../administrator/layout/footer.php'; ?>