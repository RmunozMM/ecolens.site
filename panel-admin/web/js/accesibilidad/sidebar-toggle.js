document.addEventListener("DOMContentLoaded", function () {
    // Botones para colapsar/expandir
    const menuToggle = document.getElementById("menu_toggle");   // en la top_nav
    const sidebarToggle = document.getElementById("sidebarToggle"); // opcional en sidebar-header

    const sidebar = document.getElementById("sidebar");

    function toggleSidebar() {
        if (sidebar) {
            sidebar.classList.toggle("collapsed");
        }
    }

    // Botón top_nav
    if (menuToggle) {
        menuToggle.addEventListener("click", function () {
            toggleSidebar();
        });
    }
    // Botón en sidebar (si lo dejaste)
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function () {
            toggleSidebar();
        });
    }

    // Manejo de submenús
    const expandableLinks = document.querySelectorAll(".sidebar-menu .nav-item.expandable > a");
    expandableLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const parentItem = this.parentElement;
            parentItem.classList.toggle("expanded");

            // Cerrar otros submenús abiertos
            expandableLinks.forEach(otherLink => {
                const otherParent = otherLink.parentElement;
                if (otherParent !== parentItem) {
                    otherParent.classList.remove("expanded");
                }
            });
        });
    });

    // Evitar que los enlaces de nivel 2 cierren el submenú
    const level2Links = document.querySelectorAll(".sidebar-menu .nav-item.level-2 > a");
    level2Links.forEach(link => {
        link.addEventListener("click", function (event) {
            event.stopPropagation();
        });
    });
});