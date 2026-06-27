/**
 * Maneja el envío del formulario de recuperación de contraseña.
 * Se comunica con el endpoint 'send-reset' definido en el router.php.
 */
import { handleAlert } from "../../services/ui.js";

export function initForgotPassword() {
    const form = document.getElementById("formForgotPassword");
    
    // Si el formulario no existe en la vista actual, salimos para evitar errores
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        
        // Empaquetamos los datos del formulario (el email en este caso)
        const formData = new FormData(form);

        try {
            /**
             * Enviamos la petición asíncrona.
             * El 'await' detiene la ejecución hasta que el servidor responda,
             * permitiendo un código más limpio y legible (sin .then).
             */
            handleAlert("loading");
            const response = await fetch("?url=send-reset", {
                method: "POST",
                body: formData,
            });

             handleAlert("loaded");
            // Convertimos la respuesta cruda del servidor a un objeto JSON
            const data = await response.json();

            // Delegamos la respuesta visual al servicio central de alertas
            handleAlert(data.status, data.message, data.redirect);

        } catch (error) {
            console.error("Recovery Error:", error);
            handleAlert("error", "No se pudo procesar la solicitud de recuperación.");
        }
    });
}