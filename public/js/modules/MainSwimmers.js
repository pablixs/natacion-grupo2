import { initLessonEnroll } from "./swimmers/enrollLesson.js"
import { initLessonUnenroll } from "./swimmers/unenrollLesson.js"
import { initEditProfile } from "./swimmers/editProfile.js"
// Esperamos a que el DOM esté completamente cargado para evitar errores de referencia
document.addEventListener("DOMContentLoaded", () => {
    initLessonEnroll();
    initLessonUnenroll();
    initEditProfile();
});