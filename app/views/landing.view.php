<?php
/** @var int $swimmers */
/** @var int $coaches */
/** @var int $activeYears */
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Escuela de Natación' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= rtrim(Env::get('ASSET_URL'), '/') ?>/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark primary-bg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="?url=home">
                
                <span class="fw-bold">Alpine Natación</span>
            </a>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="?url=home">Ingresar a la plataforma</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 container">

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
<section class="container py-3 bg-navy text-white rounded-3 mb-5">
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