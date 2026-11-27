// public/assets/js/validation.js
document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.querySelector('form[action*="register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => {
            const password = document.querySelector('input[name="password"]').value;
            const confirm = document.querySelector('input[name="confirm_password"]').value;
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters.');
                return;
            }
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match.');
            }
        });
    }
});