<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Escuela de Natación' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="<?= Env::get('ASSET_URL') ?>/css/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
    .profile-img-nav {
        width: 35px;
        height: 35px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #17a2b8;
    }

    .stat-card {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .class-item {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    .class-item:hover {
        background-color: #f8f9fa;
        border-left-color: #0dcaf0;
    }
    .quick-btn {
        transition: all 0.2s ease;
    }
    .quick-btn:hover {
        transform: translateY(-2px);
    }
    .activity-item {
        transition: background-color 0.2s ease;
    }
    .activity-item:hover {
        background-color: #f8f9fa;
    }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="?url=home">
                
                <span class="fw-bold">Alpine Natación</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar2">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar2">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="?url=home">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Clases</a>
                    </li>
                    <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?url=swimmers">Alumnos</a>
                        </li>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="?url=coaches">Profesores</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            <?php 
                            $foto = $_SESSION['profile_image'] ?? 'default-profile.png';
                            $rutaFoto = Env::get('ASSET_URL') . "/img/uploads/profiles/swimmers/" . $foto;
                             ?>
                            <img src="<?= $rutaFoto ?>" alt="Perfil" class="profile-img-nav me-2">
                            <span class="d-none d-lg-inline text-white"><?= htmlspecialchars($_SESSION['first_name'] ?? 'Usuario') ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="?url=logout">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                     <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?url=login">Ingresar</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 container">