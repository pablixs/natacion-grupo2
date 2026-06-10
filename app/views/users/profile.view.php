<?php include __DIR__ . '/layout/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">Mi Perfil</h4>
        </div>

        <div class="card-body">
            <form id="formUpdateProfile" action="?url=update-profile" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control"
                           value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control"
                           value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="<?= htmlspecialchars($profile['phone'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fecha de nacimiento</label>
                    <input type="date" name="birth_date" class="form-control"
                           value="<?= htmlspecialchars($profile['birth_date'] ?? '') ?>" required>
                </div>

                <?php if ($_SESSION['role_id'] == 2): ?>
                    <div class="mb-3">
                        <label class="form-label">Especialidad</label>
                        <select name="especialidad" class="form-select">
                            <option value="libre" <?= ($profile['specialty'] ?? '') == 'libre' ? 'selected' : '' ?>>Libre</option>
                            <option value="pecho" <?= ($profile['specialty'] ?? '') == 'pecho' ? 'selected' : '' ?>>Pecho</option>
                            <option value="espalda" <?= ($profile['specialty'] ?? '') == 'espalda' ? 'selected' : '' ?>>Espalda</option>
                            <option value="mariposa" <?= ($profile['specialty'] ?? '') == 'mariposa' ? 'selected' : '' ?>>Mariposa</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Foto de perfil</label>
                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-success w-100">
                    Guardar cambios
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById("formUpdateProfile").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    try {

        const response = await fetch("?url=update-profile", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        Swal.fire({
            icon: data.status,
            title: data.status === "success" ? "Éxito" : "Atención",
            text: data.message
        }).then(() => {

            if (data.redirect) {
                window.location.href = data.redirect;
            }

        });

    } catch(error) {

        console.error(error);

        Swal.fire(
            "Error",
            "No se pudo actualizar el perfil",
            "error"
        );

    }

});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>