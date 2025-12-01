/* FUNCIONES PARA TAMAÑO DE LETRA */

function actualizarTooltipsTamanioFuente(currentSize) {
    let btnDisminuir = document.getElementById("disminuirLetra");
    let btnAumentar = document.getElementById("aumentarLetra");

    if (btnDisminuir) {
        let tooltipInstance = bootstrap.Tooltip.getInstance(btnDisminuir);
        if (tooltipInstance) tooltipInstance.dispose(); // Elimina tooltip anterior
        btnDisminuir.setAttribute("title", "Disminuir a " + (currentSize - 1) + "px");
        new bootstrap.Tooltip(btnDisminuir); // Reactiva tooltip
    }
    
    if (btnAumentar) {
        let tooltipInstance = bootstrap.Tooltip.getInstance(btnAumentar);
        if (tooltipInstance) tooltipInstance.dispose(); // Elimina tooltip anterior
        btnAumentar.setAttribute("title", "Aumentar a " + (currentSize + 1) + "px");
        new bootstrap.Tooltip(btnAumentar); // Reactiva tooltip
    }
}

function cambiarTamanioFuente(factor) {
    let elementosAfectados = [
        document.body,
        document.getElementById("main-content"),
        document.getElementById("sidebar"),
        document.querySelector(".sidebar-menu"),
        ...document.querySelectorAll(".nav-link"),
        ...document.querySelectorAll(".submenu"),
        ...document.querySelectorAll(".nav-item")
    ];

    elementosAfectados.forEach(function (elemento) {
        if (elemento) {
            let style = window.getComputedStyle(elemento, null).getPropertyValue('font-size');
            let currentSize = parseFloat(style);
            let newSize = currentSize + factor;
            elemento.style.fontSize = newSize + "px";
        }
    });

    // Guardar preferencia en localStorage
    let nuevoTamanio = parseFloat(document.body.style.fontSize);
    localStorage.setItem('fontSize', nuevoTamanio + "px");

    // Actualizar tooltips con el nuevo tamaño de fuente
    actualizarTooltipsTamanioFuente(nuevoTamanio);
}

// Aplicar tamaño guardado al cargar la página
document.addEventListener("DOMContentLoaded", function () {
    let btnDisminuir = document.getElementById("disminuirLetra");
    let btnAumentar = document.getElementById("aumentarLetra");

    let savedFontSize = localStorage.getItem('fontSize');
    let currentSize = savedFontSize ? parseFloat(savedFontSize) : parseFloat(btnDisminuir.dataset.fontsize);

    document.body.style.fontSize = currentSize + "px";
    document.getElementById("main-content").style.fontSize = currentSize + "px";
    document.getElementById("sidebar").style.fontSize = currentSize + "px";

    document.querySelectorAll(".nav-link, .submenu, .nav-item").forEach(elemento => {
        elemento.style.fontSize = currentSize + "px";
    });

    // Actualizar tooltips iniciales
    actualizarTooltipsTamanioFuente(currentSize);

    btnDisminuir.addEventListener("click", function () {
        cambiarTamanioFuente(-1);
    });

    btnAumentar.addEventListener("click", function () {
        cambiarTamanioFuente(1);
    });
});

/* FUNCIONES PARA TOGLGES PARA ALTO CONTRASTE */
function toggleAltoContraste() {
document.body.classList.toggle("alto-contraste");
document.getElementById("main-content").classList.toggle("alto-contraste");
document.getElementById("sidebar").classList.toggle("alto-contraste");

let isEnabled = document.body.classList.contains("alto-contraste");
localStorage.setItem('modoAltoContraste', isEnabled ? "enabled" : "disabled");
}

document.addEventListener("DOMContentLoaded", function () {
if (localStorage.getItem('modoAltoContraste') === "enabled") {
document.body.classList.add("alto-contraste");
document.getElementById("main-content").classList.add("alto-contraste");
document.getElementById("sidebar").classList.add("alto-contraste");
}
document.getElementById("modoAltoContraste").addEventListener("click", toggleAltoContraste);
});

/* FUNCION PARA CAMBIO DE FUENTE POR DISLEXIA */
function activarModoDislexia() {
    document.body.classList.toggle("modo-dislexia");
    let isEnabled = document.body.classList.contains("modo-dislexia");
    localStorage.setItem('modoDislexia', isEnabled ? "enabled" : "disabled");
}

document.addEventListener("DOMContentLoaded", function () {
    if (localStorage.getItem('modoDislexia') === "enabled") {
        document.body.classList.add("modo-dislexia");
    }
    document.getElementById("modoDislexia").addEventListener("click", activarModoDislexia);
});