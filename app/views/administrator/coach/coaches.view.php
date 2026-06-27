<?php
include __DIR__ . '/../../administrator/layout/header.php';
/** @var array $users_data */
/** @var string $page_title */
/** @var string $register_url */
/** @var string $register_label */
/** @var string $empty_icon */
/** @var string $empty_message */
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12" style="max-width: 1100px;">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 text-navy"><?= $page_title ?></h4>
                <a href="<?= $register_url ?>" class="btn-cta">
                    <i class="fa-solid fa-plus"></i><?= $register_label ?>
                </a>
            </div>

            <?php if (count($users_data) > 0) : ?>
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Fecha de nac.</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users_data as $u): ?>
                                <tr class="<?= !$u['active'] ? 'table-secondary' : '' ?>">
                                    <td>
                                        <?php if ($u['full_name']): ?>
                                            <?= htmlspecialchars($u['full_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">Pendiente de registro</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                    <td><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
                                    <td><?= $u['birth_date'] ? date('d/m/Y', strtotime($u['birth_date'])) : '—' ?></td>
                                    <td>
                                        <?php if ($u['active']): ?>
                                            <span class="badge badge-status bg-success text-white">Activo</span>
                                        <?php else: ?>
                                            <span class="badge badge-status bg-secondary text-white">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($u['profile_created'] && $u['active']): ?>
                                            <a href="?url=edit-user&id=<?= $u['id'] ?>" class="action-btn" title="Editar">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="#" class="action-btn btn-reset-password" title="Resetear contraseña"
                                               data-user-id="<?= $u['id'] ?>"
                                               data-user-name="<?= htmlspecialchars($u['full_name']) ?>">
                                                <i class="fa-solid fa-key"></i>
                                            </a>
                                            <a href="#" class="action-btn delete btn-delete-user" title="Desactivar"
                                               data-user-id="<?= $u['id'] ?>"
                                               data-user-name="<?= htmlspecialchars($u['full_name']) ?>">
                                                <i class="fa-solid fa-user-slash"></i>
                                            </a>
                                        <?php elseif (!$u['active']): ?>
                                            <span class="text-muted small">Desactivado</span>
                                            <a href="#" class="action-btn activate btn-activate-user" title="Activar"
                                                   data-user-id="<?= $u['id'] ?>"
                                                   data-user-name="<?= htmlspecialchars($u['full_name']) ?>">
                                                    <i class="fa-solid fa-user-check"></i>
                                                </a>
                                        <?php else: ?>
                                            <span class="text-muted small">Registro pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid <?= $empty_icon ?>"></i>
                    </div>
                    <h5 class="mb-2 text-navy"><?= $empty_message ?></h5>
                    <p class="text-muted mb-4">Dá de alta uno para empezar</p>
                    <a href="<?= $register_url ?>" class="btn-cta">
                        <i class="fa-solid fa-plus"></i><?= $register_label ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include __DIR__ . '/../../administrator/layout/footer.php'; ?>