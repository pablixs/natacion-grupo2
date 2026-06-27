<?php include __DIR__ . '/../users/layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">


                <div class="card-body px-4">
                    <form id="formRegister">
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
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Mín. 6 caracteres" required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Repetir contraseña</label>
                                <input type="password" name="passwordrepeat" class="form-control"
                                    placeholder="Mín. 6 caracteres" required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" placeholder="11 1234 5678" required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="ejemplo@mail.com" required>
                            </div>

                            <div class="col mb-3 ">
                                <label class="form-label">Fecha de nacimiento</label>
                                <input type="date" name="birth_date" class="form-control"
                                    required>
                            </div>

                            <div class="col mb-3">
                                <label class="form-label">Foto de Perfil</label>
                                <input type="file" name="profile_image" class="form-control" accept="image/*">
                            </div>

                        </div>

                        <div class="row mt-3">
                            <button type="submit" class="btn-submit-form w-100 mb-3">
                                Registrar usuario
                            </button>
                        </div>
                    </form>
                </div>

                <div class="text-center pb-4">
                    <a href="?url=login" class="text-decoration-none small">
                        <i class="bi bi-arrow-left"></i> ¿Ya tenes una cuenta? Inicia sesión
                    </a>
                    <a href="?url=forgot-password" class="text-decoration-none small"><br>
                        <i class="bi bi-arrow-left"></i> ¿Olvidaste tu contraseña
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../users/layout/footer.php'; ?>