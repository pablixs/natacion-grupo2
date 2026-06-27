import { handleAlert } from '../../services/ui.js';

export function initManageLessons() {

    const editButtons = document.querySelectorAll('.btn-edit-lesson');
    const editModal   = document.getElementById('editLessonModal');

    if (editModal && editButtons.length) {
        const bsEditModal   = new bootstrap.Modal(editModal);
        const editForm      = document.getElementById('formEditLesson');
        const editLoading   = document.getElementById('editLessonLoading');
        const btnSubmitEdit = document.getElementById('btnSubmitEditLesson');

        editButtons.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const lessonId = btn.dataset.lessonId;

                editLoading.style.display = 'block';
                editForm.style.display    = 'none';
                bsEditModal.show();

                try {
                    const response = await fetch(`?url=get-lesson&id=${lessonId}`);
                    const data = await response.json();

                    if (data.status !== 'success') {
                        bsEditModal.hide();
                        handleAlert('error', data.message || 'No se pudo cargar la clase.');
                        return;
                    }

                    populateEditForm(data.lesson);
                    editLoading.style.display = 'none';
                    editForm.style.display    = 'block';
                } catch (error) {
                    bsEditModal.hide();
                    handleAlert('error', 'Error de conexión al cargar la clase.');
                }
            });
        });

        btnSubmitEdit.addEventListener('click', async () => {
            const formData = new FormData(editForm);

            try {
                const response = await fetch('?url=update-lesson', {
                    method: 'POST',
                    body: formData
                });
                const text = await response.text();
                const data = JSON.parse(text);

                bsEditModal.hide();
                handleAlert(data.status, data.message, data.redirect);
            } catch (error) {
                bsEditModal.hide();
                handleAlert('error', 'Error de conexión al guardar.');
            }
        });

        editModal.addEventListener('hidden.bs.modal', () => {
            editForm.style.display    = 'none';
            editLoading.style.display = 'block';
            editForm.reset();
        });
    }


    const studentButtons = document.querySelectorAll('.btn-view-students');
    const studentsModal  = document.getElementById('studentsModal');

    if (studentsModal && studentButtons.length) {
        const bsStudentsModal = new bootstrap.Modal(studentsModal);
        const studentsLoading = document.getElementById('studentsLoading');
        const studentsEmpty   = document.getElementById('studentsEmpty');
        const studentsTable   = document.getElementById('studentsTable');
        const studentsBody    = document.getElementById('studentsTableBody');
        const subtitle        = document.getElementById('studentsModalSubtitle');

        studentButtons.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const lessonId = btn.dataset.lessonId;

                subtitle.textContent = `${btn.dataset.lessonLevel} — ${btn.dataset.lessonSpecialties}`;

                studentsLoading.style.display = 'block';
                studentsEmpty.style.display   = 'none';
                studentsTable.style.display   = 'none';
                studentsBody.innerHTML        = '';

                bsStudentsModal.show();

                try {
                    const response = await fetch(`?url=lesson-students&lesson_id=${lessonId}`);
                    const data = await response.json();

                    studentsLoading.style.display = 'none';

                    if (data.status !== 'success') {
                        bsStudentsModal.hide();
                        handleAlert('error', data.message || 'No se pudieron cargar los alumnos.');
                        return;
                    }

                    if (data.students.length === 0) {
                        studentsEmpty.style.display = 'block';
                        return;
                    }

                    data.students.forEach((student, index) => {
                        const date = new Date(student.enrolled_at);
                        const formattedDate = date.toLocaleDateString('es-AR', {
                            day: '2-digit', month: 'short', year: 'numeric'
                        });

                        const statusBadge = student.status === 'Confirmed'
                            ? '<span class="badge badge-status bg-success text-white">Activo</span>'
                            : '<span class="badge badge-status bg-secondary text-white">Cancelado</span>';

                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${student.first_name} ${student.last_name}</td>
                                <td>${student.email}</td>
                                <td>${formattedDate}</td>
                                <td>${statusBadge}</td>
                            </tr>
                        `;
                        studentsBody.insertAdjacentHTML('beforeend', row);
                    });

                    studentsTable.style.display = 'table';
                } catch (error) {
                    bsStudentsModal.hide();
                    handleAlert('error', 'Error de conexión al cargar alumnos.');
                }
            });
        });
    }

    const deleteButtons = document.querySelectorAll('.btn-delete-lesson');
    const deleteModal   = document.getElementById('deleteModal');

    if (deleteModal && deleteButtons.length) {
        const bsDeleteModal    = new bootstrap.Modal(deleteModal);
        const btnConfirmDelete = document.getElementById('btnConfirmDelete');
        const deleteDesc       = document.getElementById('deleteModalDescription');
        let lessonIdToDelete   = null;

        deleteButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                lessonIdToDelete = btn.dataset.lessonId;

                deleteDesc.textContent = 
                    `Se eliminará la clase "${btn.dataset.lessonLevel} — ${btn.dataset.lessonSpecialties}" ` +
                    `y todas sus inscripciones. Esta acción no se puede deshacer.`;

                bsDeleteModal.show();
            });
        });

        btnConfirmDelete.addEventListener('click', async () => {
            if (!lessonIdToDelete) return;

            const formData = new FormData();
            formData.append('lesson_id', lessonIdToDelete);

            try {
                const response = await fetch('?url=delete-lesson', {
                    method: 'POST',
                    body: formData
                });
                const text = await response.text();
                const data = JSON.parse(text);

                bsDeleteModal.hide();
                handleAlert(data.status, data.message, data.redirect);
            } catch (error) {
                bsDeleteModal.hide();
                handleAlert('error', 'Error de conexión al eliminar.');
            }
        });

        deleteModal.addEventListener('hidden.bs.modal', () => {
            lessonIdToDelete = null;
        });
    }
}


function populateEditForm(lesson) {
    document.getElementById('editLessonId').value   = lesson.id;
    document.getElementById('editCoachId').value    = lesson.coach_id;
    document.getElementById('editLevel').value      = lesson.level;
    document.getElementById('editFirstDay').value   = lesson.first_day_of_week;
    document.getElementById('editSecondDay').value  = lesson.second_day_of_week || '';
    document.getElementById('editStartTime').value  = lesson.start_time?.substring(0, 5) || '';
    document.getElementById('editEndTime').value    = lesson.end_time?.substring(0, 5) || '';
    document.getElementById('editCapacity').value   = lesson.capacity;
    document.getElementById('editActive').checked   = lesson.active == 1;

    document.querySelectorAll('.edit-specialty').forEach(cb => {
        cb.checked = lesson.specialty_ids.includes(parseInt(cb.value));
    });
}