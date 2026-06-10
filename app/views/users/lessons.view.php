<?php include __DIR__ . '/layout/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-center">Clases disponibles</h4>
        </div>

        <div class="card-body">
            <?php if (empty($lessons)): ?>
                <div class="alert alert-info">
                    No hay clases disponibles por el momento.
                </div>
            <?php else: ?>

                <div class="row">
                    <?php foreach ($lessons as $lesson): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        Nivel: <?= htmlspecialchars($lesson['level']) ?>
                                    </h5>

                                    <p class="mb-1">
                                        <strong>Día:</strong>
                                        <?= htmlspecialchars($lesson['day_of_week']) ?>
                                    </p>

                                    <p class="mb-1">
                                        <strong>Horario:</strong>
                                        <?= htmlspecialchars($lesson['start_time']) ?>
                                        a
                                        <?= htmlspecialchars($lesson['end_time']) ?>
                                    </p>

                                    <p class="mb-1">
                                        <strong>Profesor responsable:</strong>
                                        <?= htmlspecialchars($lesson['coach_first_name'] . ' ' . $lesson['coach_last_name']) ?>
                                    </p>

                                    <p class="mb-3">
                                        <strong>Cupos:</strong>
                                        <?= htmlspecialchars($lesson['capacity']) ?>
                                    </p>

                                    <form class="formEnrollLesson" method="POST" action="?url=enroll-lesson">
                                        <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                                        <button type="submit" class="btn btn-success w-100">
                                            Inscribirme
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll(".formEnrollLesson").forEach(form => {
    form.addEventListener("submit", async function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        try {
            const response = await fetch("?url=enroll-lesson", {
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

        } catch (error) {
            Swal.fire("Error", "No se pudo procesar la inscripción.", "error");
        }
    });
});
</script>

<?php include __DIR__ . '/layout/footer.php'; ?>