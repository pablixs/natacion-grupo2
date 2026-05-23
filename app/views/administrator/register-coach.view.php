<?php
include __DIR__ . '/../users/layout/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center"><?php echo $title ?? 'Registro de Swimmer'; ?></h4>
                </div>
                <div class="card-body">
                    <form id="formRegisterCoach" action="?url=create-coach" method="POST" enctype="multipart/form-data">
                        <div class="row row-cols-1 row-cols-md-2">
                            <div class="col mb-3 w-100">
                                <p>Para dar de alta un profesor debes ingresar su correo y la especialidad. Se le asignará un usuario y luego el profesor deberá completar el registro manualmente.</p>
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
                                <button type="submit" class="btn btn-primary px-5">Registrar profesor</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../users/layout/footer.php'; ?>