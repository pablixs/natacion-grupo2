/**
 * Gestión del alta de alumnos mediante AJAX.
 * Este módulo captura los datos del formulario, valida archivos en el cliente
 * y los envía al controlador mediante la API Fetch.
 */
import { handleAlert } from "../../services/ui.js";

export function initTable() {
  const $ = window.$;

  $('#myTable').DataTable({
  });

}


