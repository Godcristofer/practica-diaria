const items = document.querySelectorAll(".carousel__item");
const prev = document.getElementById("prev");
const next = document.getElementById("next");

let currentIndex = 0; // Índice del elemento activo

function updateCarousel() {
    items.forEach((item, index) => {
        const totalItems = items.length;

        // Calcula la posición relativa respecto al índice actual
        const relativeIndex = (index - currentIndex + totalItems) % totalItems;

        // Ajusta las posiciones según el índice relativo
        if (relativeIndex === 0) {
            item.style.transform = "translateX(0px) scale(1)";
            item.style.opacity = "1";
            item.style.zIndex = "2"; // Activo
        } else if (relativeIndex === 1 || relativeIndex === totalItems - 1) {
            const direction = relativeIndex === 1 ? 1 : -1; // Izquierda (-1) o derecha (+1)
    item.style.transform = `translateX(${direction * 320}px) scale(0.8)`;
    item.style.opacity = "0.7";
    item.style.zIndex = "1";
        } else {
            const offset = relativeIndex < totalItems / 2 ? relativeIndex : relativeIndex - totalItems;
            item.style.transform = `translateX(${offset * 320}px) scale(0.6)`;
            item.style.opacity = "0.5";
            item.style.zIndex = "0"; // Fondo
        }
    });
}

// Botón para mover a la izquierda
prev.addEventListener("click", () => {
    currentIndex = (currentIndex - 1 + items.length) % items.length;
    updateCarousel();
});

// Botón para mover a la derecha
next.addEventListener("click", () => {
    currentIndex = (currentIndex + 1) % items.length;
    updateCarousel();
});

// Inicializa el carrusel
updateCarousel();
