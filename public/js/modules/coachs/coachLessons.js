import { handleAlert } from "../../services/ui.js";

export function initCoachLessons() {
    // Cargar alumnos cuando se abre un modal
    document.querySelectorAll('[id^="studentsModal-"]').forEach((modal) => {
        modal.addEventListener("show.bs.modal", async (e) => {
            const button = e.relatedTarget;
            const lessonId = button?.dataset?.lessonId;
            const listContainer = modal.querySelector('[id^="studentsList-"]');

            if (!lessonId || !listContainer) return;

            // Si ya se cargaron, no volver a pedir
            if (listContainer.dataset.loaded === "true") return;

            try {
                const response = await fetch(`?url=lesson-students&lesson_id=${lessonId}`);
                const data = await response.json();

                if (data.status === "success" && data.students.length > 0) {
                    listContainer.innerHTML = data.students
                        .map((s) => `
                            <div class="student-row">
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <span class="student-name">${s.last_name}, ${s.first_name}</span>
                                    </div>
                                </div>
                                <div class="student-phone text-muted">
                                    <i class="fa-solid fa-phone me-1"></i>${s.phone}
                                </div>
                            </div>
                        `)
                        .join("");
                    
                    listContainer.dataset.loaded = "true";
                } else {
                    listContainer.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fa-solid fa-users text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No hay alumnos inscriptos</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error("Error al cargar alumnos:", error);
                listContainer.innerHTML = `
                    <div class="text-center py-3 text-danger">
                        <small>Error al cargar los alumnos</small>
                    </div>
                `;
            }
        });
    });
}