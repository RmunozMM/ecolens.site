document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu_toggle");
    const sidebar = document.getElementById("sidebar");

    function toggleSidebar() {
        document.body.classList.toggle("sidebar-collapsed");
    }

    if (menuToggle) {
        menuToggle.addEventListener("click", toggleSidebar);
    }

    // --- Submenús (sin cambios)
    const expandableLinks = document.querySelectorAll(".sidebar-menu .nav-item.expandable > a");
    expandableLinks.forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            const parentItem = this.parentElement;
            parentItem.classList.toggle("expanded");
            expandableLinks.forEach(otherLink => {
                const otherParent = otherLink.parentElement;
                if (otherParent !== parentItem) {
                    otherParent.classList.remove("expanded");
                }
            });
        });
    });
    const level2Links = document.querySelectorAll(".sidebar-menu .nav-item.level-2 > a");
    level2Links.forEach(link => {
        link.addEventListener("click", function (event) {
            event.stopPropagation();
        });
    });
});
