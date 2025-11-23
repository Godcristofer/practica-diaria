// Alternar entre formularios
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.querySelector('.login');
    const registerForm = document.querySelector('.registro');
    const loginLink = document.getElementById('login-enlace');
    const registerLink = document.getElementById('registrar-enlace');

    //Alternar a formulario a registro
    registerLink.addEventListener('click', function (e) {
        e.preventDefault();
        loginForm.classList.remove('active');
        registerForm.classList.add('active');
    });

    //Alternar a formulario de inicio de sesion
    loginLink.addEventListener('click', function (e) {
        e.preventDefault();
        registerForm.classList.remove('active');
        loginForm.classList.add('active');
    });

    // Mostrar login por defecto
    loginForm.classList.add('active');
});

// Añade feedback visul a tiempo real
const passwordInput = document.getElementById('contrasena_registro');
const confirmPasswordInput = document.getElementById('repetir_contrasena');
const message = document.getElementById('password-message');

// Escucha eventos de entrada en el campo de confirmación de contraseña
confirmPasswordInput.addEventListener('input', function () {
    if (passwordInput.value !== confirmPasswordInput.value) {
        message.textContent = 'Las contraseñas no coinciden';
        message.style.color = 'red';
    } else {
        message.textContent = 'Las contraseñas coinciden';
        message.style.color = 'green';
    }
});