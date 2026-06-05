/**
 * Gestiona el paso final de la recuperación: el cambio de contraseña.
 * Envía el token (oculto) y la nueva clave al método 'update-password'.
 */
import { handleAlert } from '../../services/ui.js';

export function initResetPassword() {
    const form = document.getElementById('formResetPassword');
    
    // Verificamos presencia del formulario para evitar errores en otras vistas
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);

        try {
            /**
             * La petición viaja al endpoint 'update-password'.
             * Es crucial que el formulario incluya el token en un <input type="hidden">.
             */
            handleAlert("loading");
            const response = await fetch('?url=update-password', { 
                method: 'POST', 
                body: formData 
            });

            const data = await response.json();
            
            // Si el cambio es exitoso, handleAlert redirigirá al login
            handleAlert(data.status, data.message, data.redirect);

        } catch (error) {
            console.error("Password Update Error:", error);
            handleAlert('error', 'Error al intentar actualizar la contraseña.' . error);
        }
    });
}