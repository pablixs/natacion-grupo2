<?php 
include __DIR__ . '/users/layout/header.php'; 
/** @var string $name */
?>

<section class="container d-flex align-items-center">
    <div class="row align-items-center">
        <!-- Texto -->
        <div class="col-md-6">
            <h1 class="text-blue-primary display-3 fw-bold mb-4">
                Alpine Swimming School
            </h1>
            <p class="text-blue-secondary lead mb-4">
                Entrená con pasión, superá tus límites y llevá tu rendimiento al siguiente nivel.
            </p>
        </div>
        <!-- Imagen -->
        <div class="col-md-6 text-center">
            <img 
                src="<?= _URL ?>/public/img/logo/logo_alpine.png"
                class="img"
                style="max-width: 400px;"
                alt="Logo Alpine Swimming School"
            >
        </div>
    </div>
</section>

<!-- ABOUT -->
<section class="container p-4 bg-subtle rounded-3">
    <div class="text-center mb-5">
        <h2 class="fw-bold">¿Quiénes somos?</h2>
        <p class="text-gray-primary">
            Un club de natación enfocado en el crecimiento deportivo y personal de cada nadador.
            Nuestro objetivo es sacar el máximo potencial de cada atleta, brindando un ambiente de apoyo, compañerismo y atención personalizada a las necesidades de nuestros alumnos.
        </p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card bg-cian-primary h-100 shadow-card border-0">
                <div class="card-body text-center text-gray-light">
                    <h4 class="mb-3">Entrenadores</h4>
                    <p>
                        Profesionales capacitados para acompañarte en cada etapa de tu desarrollo.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-cian-primary h-100 shadow-card border-0">
                <div class="card-body text-center text-gray-light">
                    <h4 class="mb-3">Clases</h4>
                    <p>
                        Distintos niveles y horarios adaptados a niños, jóvenes y adultos.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-cian-primary h-100 shadow-card border-0">
                <div class="card-body text-center text-gray-light">
                    <h4 class="mb-3">Competencias</h4>
                    <p>
                        Participación en torneos y preparación física orientada al alto rendimiento.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="container py-3 bg-blue-primary text-white rounded-3">
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <h2 class="display-5 fw-bold"><?= $swimmers ?></h2>
            <p>Nadadores activos</p>
        </div>
        <div class="col-md-4 mb-4">
            <h2 class="display-5 fw-bold"><?= $coaches ?></h2>
            <p>Entrenadores profesionales</p>
        </div>
        <div class="col-md-4 mb-4">
            <h2 class="display-5 fw-bold"><?= $activeYears ?></h2>
            <p>Años formando atletas</p>
        </div>
    </div>
</section>
<?php include __DIR__ . '/users/layout/footer.php'; ?>
