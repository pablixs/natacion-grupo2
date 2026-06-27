/**
 * Centraliza la inicialización de todos los formularios relacionados con usuarios.
 * Se carga como type="module" en el layout principal.
 */

import { initTable } from "./admin/tableManageUsers.js";
import { initRegisterCoach } from "./admin/formRegisterCoach.js"
import { initRegisterSwimmer } from "./admin/formRegisterSwimmer.js"
import { initNewLesson } from "./admin/formNewLesson.js"
import { initManageLessons } from "./admin/formEditLesson.js"
import { initManageUsers } from "./admin/formManageUsers.js"
// Esperamos a que el DOM esté completamente cargado para evitar errores de referencia
document.addEventListener("DOMContentLoaded", () => {
    
    // Inicializamos cada funcionalidad. 
    // Cada módulo interno se encargará de verificar si su formulario existe en la vista actual.
    initTable();
    initRegisterCoach();
    initRegisterSwimmer();
    initNewLesson();
    initManageLessons();
    initManageUsers();
    console.log("Admin module initialized successfully.");
});