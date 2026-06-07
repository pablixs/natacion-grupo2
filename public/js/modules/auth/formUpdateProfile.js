import { handleAlert } from "../../services/ui.js";

export function initUpdateProfile() {
    const form = document.getElementById("formUpdateProfile");

    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
            const response = await fetch("?url=update-profile", {
                method: "POST",
                body: formData,
            });

            const data = await response.json();

            handleAlert(data.status, data.message, data.redirect);
        } catch (error) {
            console.error(error);
            handleAlert("error", "No se pudo actualizar el perfil.");
        }
    });
}