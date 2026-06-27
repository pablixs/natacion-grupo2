export function initTogglePassword() {
    document.querySelectorAll('.toggle-password').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const form = checkbox.closest('form');
            form.querySelectorAll('input[type="password"], input[data-pw]').forEach(input => {
                if (checkbox.checked) {
                    input.setAttribute('data-pw', '');
                    input.type = 'text';
                } else {
                    input.removeAttribute('data-pw');
                    input.type = 'password';
                }
            });
        });
    });
}