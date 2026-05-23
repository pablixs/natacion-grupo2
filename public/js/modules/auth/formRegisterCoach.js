/**
 * Gestión del alta de alumnos mediante AJAX.
 * Este módulo captura los datos del formulario, valida archivos en el cliente
 * y los envía al controlador mediante la API Fetch.
 */
import { handleAlert } from "../../services/ui.js";

export function initRegisterCoach() {
    const formCoach = document.getElementById("formRegisterCoach");
    console.log("console log antes del if: ", formCoach);
    if (!formCoach) return;
    formCoach.addEventListener("submit", async (e) => {
        e.preventDefault();
        /**
         * FormData empaqueta automáticamente todos los campos del formulario,
         * incluyendo los archivos (files), siempre que el input tenga el atributo 'name'.
         */
        const formData = new FormData(formCoach);

        console.log("datos para el sevidor: ", formData)
        try {

            const response = await fetch("?url=create-coach", {
                method: "POST",
                body: formData,
            });

            const text = await response.text();
            console.log("aaaaaaaaaaaaaa text antes del try: ", text);
            try {
                const data = JSON.parse(text);
                console.log("data antes del handleAlert: ", data);
                // El servidor retornará el status (success, error, warning) y el mensaje
                handleAlert(data.status, data.message, data.redirect);
            } catch (err) {
                // En caso de un error crítico de PHP (Fatal Error), la respuesta no será un JSON válido
                console.error("Respuesta inesperada del servidor:", text);
                handleAlert(
                    "error",
                    "Error crítico en el servidor. Revisar consola de red.",
                );
            }

        } catch (error) {
            console.error("Error en la conexión Fetch:", error);
            handleAlert("error", "No se pudo establecer conexión con el servidor.");
        }
    });
}
