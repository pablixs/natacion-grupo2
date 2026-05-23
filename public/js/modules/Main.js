/**
 * Centraliza la inicialización de todos los formularios relacionados con usuarios.
 * Se carga como type="module" en el layout principal.
 */
import { initLogin } from "./auth/formLogin.js";
import { initRegister } from "./auth/formRegister.js";
import { initForgotPassword } from "./auth/formForgotPassword.js";
import { initResetPassword } from "./auth/formResetPassword.js";
import { initRegisterCoach } from "./auth/formRegisterCoach.js";
import { initSaveProfileCoach } from "./auth/formSaveProfileCoach.js";
// Esperamos a que el DOM esté completamente cargado para evitar errores de referencia
document.addEventListener("DOMContentLoaded", () => {
    
    // Inicializamos cada funcionalidad. 
    // Cada módulo interno se encargará de verificar si su formulario existe en la vista actual.
    initLogin();
    initRegister();
    initRegisterCoach();
    initForgotPassword();
    initResetPassword();
    initSaveProfileCoach();
    console.log("Auth module initialized successfully.");
});