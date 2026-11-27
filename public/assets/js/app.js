// public/assets/js/app.js
document.addEventListener('DOMContentLoaded', () => {
    // Flash message auto-hide
    const flash = document.querySelector('.flash-message');
    if (flash) {
        setTimeout(() => { flash.style.display = 'none'; }, 5000);
    }

    // Password visibility toggle
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.target);
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
            }
        });
    });
});