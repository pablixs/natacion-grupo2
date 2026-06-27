import { handleAlert } from "../../services/ui.js";

export function initLessonEnroll() {
    document.addEventListener("submit", async (e) => {
        const form = e.target.closest("[data-form='enroll-lesson']");
        console.log(form)
        if (!form) return;
        e.preventDefault();

        const formData = new FormData(form);
        const modal = form.closest(".modal");

        try {
            handleAlert("loading");

            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            }

            const response = await fetch("?url=lesson-enroll-new", {
                method: "POST",
                body: formData,
            });

            const text = await response.text();
            try {
                handleAlert("loaded");
                const data = JSON.parse(text);
                handleAlert(data.status, data.message, data.redirect);
            } catch (err) {
                console.error("Respuesta inesperada del servidor:", text);
                handleAlert("error", "Error crítico en el servidor. Revisar consola de red.");
            }

        } catch (error) {
            console.error("Error en la conexión Fetch:", error);
            handleAlert("error", "No se pudo establecer conexión con el servidor.");
        }
    });
}