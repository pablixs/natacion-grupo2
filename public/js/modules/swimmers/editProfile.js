import { handleAlert } from "../../services/ui.js";

export function initEditProfile() {
    const form = document.getElementById("formEditProfile");

    const inputImage = document.getElementById("profile_image");
    const avatarPreview = document.getElementById("avatarPreview");

    if (inputImage && avatarPreview) {
        inputImage.addEventListener("change", (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const allowedTypes = ["image/jpeg", "image/png"];
            if (!allowedTypes.includes(file.type)) {
                handleAlert("error", "Solo se permiten imágenes JPG o PNG.");
                inputImage.value = "";
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                handleAlert("warning", "La imagen no puede superar los 2MB.");
                inputImage.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = (ev) => {
                avatarPreview.src = ev.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    if (!form) return;
    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        /**
         * Capturamos el archivo de imagen para validarlo antes de enviarlo.
         * Es una buena práctica para ahorrar ancho de banda y no saturar el servidor
         * con archivos que no cumplen los requisitos.
         */
        const fileInput = form.querySelector('input[name="profile_image"]');
        const file = fileInput ? fileInput.files[0] : null;

        if (file) {
            // Validamos que el formato sea exclusivamente de imagen
            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            if (!allowedTypes.includes(file.type)) {
                return handleAlert(
                    "error",
                    "Formato no válido. Solo se permiten imágenes JPG, PNG o GIF.",
                );
            }

            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                return handleAlert(
                    "warning",
                    "La imagen es muy pesada. El límite es de 2MB.",
                );
            }
        }

        const formData = new FormData(form);

        try {
            handleAlert("loading");

            const response = await fetch("?url=update-profile", {
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


