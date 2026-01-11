
function togglePassword() {
    const passwordField = document.getElementById('mdp');
    const eyeIcon = document.getElementById('eye-icon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" /><line x1="1" y1="1" x2="23" y2="23" />'; // Œil barré pour masquer
    } else {
        passwordField.type = 'password';
        eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" />'; // Œil normal pour afficher
    }
}