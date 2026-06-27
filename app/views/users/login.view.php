<?php include __DIR__ . '/../users/layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 420px;">

            <div class="text-center mb-4">
                <h4 class="login-title">Alpine Natación</h4>
                <p class="text-muted small">Ingresá a tu cuenta para continuar</p>
            </div>

            <div class="form-card">
                <div class="form-card-body">
                    <form id="formLogin">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="tu@email.com" required>
                            </div>
                            <div class="col mb-3">
                                <label class="form-label">Contraseña</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" placeholder="Mín. 6 caracteres" required>
                                    <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn-submit-form w-100 mb-3">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Iniciar sesión
                            </button>
                    </form>
                    <div class="text-center">
                        <a href="?url=register" class="text-decoration-none small">
                            <i class="bi bi-arrow-left"></i> ¿No tenes una cuenta? Registrate
                        </a>
                        <a href="?url=forgot-password" class="text-decoration-none small"><br>
                            <i class="bi bi-arrow-left"></i> ¿Olvidaste tu contraseña
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../users/layout/footer.php'; ?>