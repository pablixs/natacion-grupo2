/**
 * Centraliza la inicialización de todos los formularios relacionados con usuarios.
 * Se carga como type="module" en el layout principal.
 */

import { initTable } from "./admin/tableManageUsers.js";
import { initRegisterCoach } from "./admin/formRegisterCoach.js"
// Esperamos a que el DOM esté completamente cargado para evitar errores de referencia
document.addEventListener("DOMContentLoaded", () => {
    
    // Inicializamos cada funcionalidad. 
    // Cada módulo interno se encargará de verificar si su formulario existe en la vista actual.
    initTable();
    initRegisterCoach();
    console.log("Auth module initialized successfully.");
});