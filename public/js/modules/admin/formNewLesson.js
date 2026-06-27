import { handleAlert } from "../../services/ui.js";

export function initNewLesson() {
    const formNewLesson = document.getElementById("formNewLesson");
    console.log("console log antes del if: ", formNewLesson);
    if (!formNewLesson) return;
    formNewLesson.addEventListener("submit", async (e) => {
        e.preventDefault();
        

        const formData = new FormData(formNewLesson);
        console.log(formData);
           try {

            handleAlert("loading");
            const response = await fetch("?url=create-lesson", {
                method: "POST",
                body: formData,
            });
            console.log("response: " , response)

            const text = await response.text();
            try {
                handleAlert("loaded");
                const data = JSON.parse(text);
                console.log("data antes del handleAlert: ", data);
                // El servidor retornará el status (success, error, warning) y el mensaje
                handleAlert(data.status, data.message, data.redirect);
            } catch (err) {
                handleAlert("loading");
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
