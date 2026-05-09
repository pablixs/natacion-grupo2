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
                        <input type="hidden" name="role_id" value="2">
                        <div class="row row-cols-1 row-cols-md-2">
                            <div class="col mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ej: Juan"
                                    required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Apellido</label>
                                <input type="text" name="apellido" class="form-control" placeholder="Ej: Pérez"
                                    required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" placeholder="juan@correo.com"
                                    required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" placeholder="11 1234 5678" required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Mín. 6 caracteres" required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Repetir contraseña</label>
                                <input type="password" name="passwordrepeat" class="form-control"
                                    placeholder="Mín. 6 caracteres" required>
                            </div>

                            <div class="col mb-3 w-100">
                                <label for="especialidad" class="form-label">Especialidad</label>
                                <select class="form-select" aria-label="Selecciona una especialidad" name="especialidad">
                                    <option selected disabled>Selecciona una opción </option>
                                    <option value="libre">Libre</option>
                                    <option value="pecho">Pecho</option>
                                    <option value="espalda">Espalda</option>
                                    <option value="mariposa">Mariposa</option>
                                </select>
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