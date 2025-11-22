
// Validación mejorada de formularios y manejo de errores del servidor
document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll("form");

    function showError(input, message) {
        // eliminar mensajes previos
        clearError(input);
        const span = document.createElement("span");
        span.classList.add("error-message");
        span.style.color = "red";
        span.textContent = message;
        input.insertAdjacentElement("afterend", span);
    }

    function clearError(input) {
        const next = input.nextElementSibling;
        if (next && next.classList && next.classList.contains("error-message")) {
            next.remove();
        }
    }

    forms.forEach(form => {
        form.addEventListener("submit", (e) => {
            let valid = true;
            const inputs = form.querySelectorAll("input[required], textarea[required]");
            inputs.forEach(input => {
                clearError(input);
                const value = input.value.trim();
                if (value === "") {
                    valid = false;
                    showError(input, "Este campo es obligatorio");
                    return;
                }

                if (input.type === "email") {
                    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!regex.test(value)) {
                        valid = false;
                        showError(input, "Ingrese un correo válido");
                        return;
                    }
                }

                if (input.type === "password" && value.length < 6) {
                    valid = false;
                    showError(input, "La contraseña debe tener al menos 6 caracteres");
                    return;
                }
            });

            if (!valid) e.preventDefault();
        });
    });

    // Mostrar mensajes del servidor si vienen en los query params
    const params = new URLSearchParams(window.location.search);
    if (params.has('error')) {
        const serverError = document.getElementById('serverError');
        if (serverError) {
            serverError.textContent = 'Credenciales inválidas o error en el servidor. Intenta de nuevo.';
            serverError.style.display = 'block';
        } else {
            // fallback: usar alert
            console.warn('serverError element not found');
        }
    }
});
