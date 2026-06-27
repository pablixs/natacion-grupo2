import { handleAlert } from '../../services/ui.js';

export function initManageUsers() {

    // FORMULARIO DE EDICION 
    const editForm = document.getElementById('formEditUser');

    if (editForm) {
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(editForm);

            try {
                const response = await fetch('?url=update-user', {
                    method: 'POST',
                    body: formData
                });
                const text = await response.text();
                const data = JSON.parse(text);
                handleAlert(data.status, data.message, data.redirect);
            } catch (error) {
                handleAlert('error', 'Error de conexión al guardar.');
            }
        });
    }

    // DESACTIVAR USUARIO (en listados)
    document.querySelectorAll('.btn-delete-user').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            const userId = btn.dataset.userId;
            const userName = btn.dataset.userName;

            const result = await Swal.fire({
                title: '¿Desactivar usuario?',
                html: `<p class="mb-1"><strong>${userName}</strong></p>
                       <p class="text-muted small">No podrá iniciar sesión. Sus datos se conservarán en el sistema.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Desactivar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc3545'
            });

            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append('user_id', userId);

            try {
                const response = await fetch('?url=delete-user', {
                    method: 'POST',
                    body: formData
                });
                const text = await response.text();
                const data = JSON.parse(text);

                handleAlert(data.status, data.message);

                if (data.status === 'success') {
                    setTimeout(() => location.reload(), 500);
                }

            } catch (error) {
                handleAlert('error', 'Error de conexión al desactivar.');
            }
        });
    });

    // ACTIVAR USUARIO (en listados)
    document.querySelectorAll('.btn-activate-user').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            const userId = btn.dataset.userId;
            const userName = btn.dataset.userName;

            const result = await Swal.fire({
                title: '¿Activar usuario?',
                html: `<p class="mb-1"><strong>${userName}</strong></p>
                       <p class="text-muted small">Se volverá a activar este usuario y podrá iniciar sesión.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Activar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#198754'
            });

            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append('user_id', userId);

            try {
                const response = await fetch('?url=activate-user', {
                    method: 'POST',
                    body: formData
                });
                const text = await response.text();
                const data = JSON.parse(text);

                handleAlert(data.status, data.message);

                if (data.status === 'success') {
                    setTimeout(() => location.reload(), 500);
                }

            } catch (error) {
                handleAlert('error', 'Error de conexión al desactivar.');
            }
        });
    });

    // RESETEAR CONTRASEÑA
    document.querySelectorAll('.btn-reset-password').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            const userId = btn.dataset.userId;
            const userName = btn.dataset.userName;

            const result = await Swal.fire({
                title: 'Resetear contraseña',
                html: `<p class="mb-1"><strong>${userName}</strong></p>
                       <p class="text-muted small">Se enviará un email con un enlace para cambiar la contraseña. Expira en 1 hora.</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Enviar email',
                cancelButtonText: 'Cancelar'
            });

            if (!result.isConfirmed) return;

            const formData = new FormData();
            formData.append('user_id', userId);

            try {
                const response = await fetch('?url=admin-reset-password', {
                    method: 'POST',
                    body: formData
                });
                const text = await response.text();
                const data = JSON.parse(text);
                handleAlert(data.status, data.message);
            } catch (error) {
                handleAlert('error', 'Error de conexión al enviar el email.');
            }
        });
    });
}