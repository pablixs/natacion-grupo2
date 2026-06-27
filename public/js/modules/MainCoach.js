import { initCoachLessons } from "./coachs/coachLessons.js"

// Esperamos a que el DOM esté completamente cargado para evitar errores de referencia
document.addEventListener("DOMContentLoaded", () => {
    initCoachLessons();
});