
// Validación mejorada de formularios
document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", (e) => {
            let valid = true;
            const inputs = form.querySelectorAll("input[required], textarea[required]");
            inputs.forEach(input => {
                const errorMsg = input.nextElementSibling;
                if (input.value.trim() === "") {
                    valid = false;
                    if (!errorMsg || !errorMsg.classList.contains("error-message")) {
                        const span = document.createElement("span");
                        span.classList.add("error-message");
                        span.style.color = "red";
                        span.textContent = "Este campo es obligatorio";
                        input.insertAdjacentElement("afterend", span);
                    }
                } else {
                    if (errorMsg && errorMsg.classList.contains("error-message")) {
                        errorMsg.remove();
                    }
                }
                // Validar email
                if (input.type === "email") {
                    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!regex.test(input.value)) {
                        valid = false;
                        if (!errorMsg || !errorMsg.classList.contains("error-message")) {
                            const span = document.createElement("span");
                            span.classList.add("error-message");
                            span.style.color = "red";
                            span.textContent = "Ingrese un correo válido";
                            input.insertAdjacentElement("afterend", span);
                        }
                    }
                }
                // Validar contraseña mínima de 6 caracteres
                if (input.type === "password" && input.value.length < 6) {
                    valid = false;
                    if (!errorMsg || !errorMsg.classList.contains("error-message")) {
                        const span = document.createElement("span");
                        span.classList.add("error-message");
                        span.style.color = "red";
                        span.textContent = "La contraseña debe tener al menos 6 caracteres";
                        input.insertAdjacentElement("afterend", span);
                    }
                }
            });
            if (!valid) e.preventDefault();
        });
    });
});
