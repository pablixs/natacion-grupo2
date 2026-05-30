<?php include __DIR__ . '/layout/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">Mi Perfil</h4>
        </div>

        <div class="card-body">
            <form id="formProfile" action="?url=update-profile" method="POST">

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="<?= htmlspecialchars($swimmer['first_name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control"
                           value="<?= htmlspecialchars($swimmer['last_name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="<?= htmlspecialchars($swimmer['phone'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha de nacimiento</label>
                    <input type="date" name="birth_date" class="form-control"
                           value="<?= htmlspecialchars($swimmer['birth_date'] ?? '') ?>" required>
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Guardar cambios
                </button>

            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>