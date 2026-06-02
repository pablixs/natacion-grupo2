<?php
include __DIR__ . '/../../administrator/layout/header.php';
/* Se declara la variable y el tipo para que intelephense no marque error de variable no definida */
/** @var array $swimmers_data */
?>

<div class="bg-white p-5 rounded shadow-sm">
    <div class="row">
        <div class="my-2">
            <a href="?url=register-swimmer" class="btn btn-primary">Dar de alta alumno</a>
        </div>

        <div class="col-md-4">
            <h2>Listado de Nadadores</h2>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Completo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($swimmers_data as $s): ?>
                        <tr>
                            <td><?= $s['id']; ?></td>
                            <td><?= $s['full_name'] ?></td>
                            <td><?= $s['email']; ?></td>
                            <td><?= $s['phone']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-info">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../../administrator/layout/footer.php'; ?>