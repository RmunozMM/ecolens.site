// scripts.js

document.addEventListener("DOMContentLoaded", function () {
  // Actualizar año en el footer
  const currentYearSpan = document.getElementById("current-year");
  if (currentYearSpan) {
    currentYearSpan.textContent = new Date().getFullYear();
  }

  // Lógica para menú hamburguesa
  const navToggle = document.getElementById("navToggle"); // Usa ID
  const mainNav = document.getElementById("mainNav"); // Usa ID

  if (navToggle && mainNav) {
    navToggle.addEventListener("click", (e) => {
      e.stopPropagation(); // Evita que el clic se propague al document
      mainNav.classList.toggle("active");
      navToggle.classList.toggle("active");
    });

    // Opcional: Cerrar menú si se hace clic en un enlace del menú (en móvil)
    mainNav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        if (mainNav.classList.contains("active")) {
          mainNav.classList.remove("active");
          navToggle.classList.remove("active");
        }
      });
    });

    // Opcional: Cerrar menú si se hace clic fuera de él
    document.addEventListener("click", function (event) {
      const isClickInsideNav = mainNav.contains(event.target);
      const isClickOnToggle = navToggle.contains(event.target);

      if (
        !isClickInsideNav &&
        !isClickOnToggle &&
        mainNav.classList.contains("active")
      ) {
        mainNav.classList.remove("active");
        navToggle.classList.remove("active");
      }
    });
  }

  // Hint para el formulario de contacto HTML (si se usa estático)
  const contactForm = document.getElementById("contact-form-html");
  if (contactForm) {
    contactForm.addEventListener("submit", function (event) {
      console.log(
        "Formulario HTML enviado. Implementar backend para envío real."
      );
      if (!contactForm.checkValidity()) {
        console.log("HTML5 validation failed.");
      }
      // event.preventDefault(); // Descomentar para evitar envío real mientras se prueba
    });
  }

  console.log("Site script loaded.");
});
