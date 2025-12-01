// Variable global para simular la sesión (Usada por el header)
const isLoggedIn = localStorage.getItem("ecoLensLoggedIn") === "true";

document.addEventListener("DOMContentLoaded", () => {
  // --- FUNCIONES DE SESIÓN ---
  const login = () => {
    localStorage.setItem("ecoLensLoggedIn", "true");
  };
  const logout = () => {
    localStorage.setItem("ecoLensLoggedIn", "false");
    window.location.href = "index.html";
  };

  // Definición del header y footer dinámicos
  let navLinksHTML = `
        <li><a href="index.html" class="nav-link">Inicio</a></li>
        <li><a href="detectar.html" class="nav-link">Detectar</a></li>
        <li><a href="nosotros.html" class="nav-link">Nosotros</a></li>
        <li><a href="contacto.html" class="nav-link">Contacto</a></li>
    `;

  if (isLoggedIn) {
    navLinksHTML += `
            <li><a href="detecciones.html" class="nav-link">Mis Detecciones</a></li>
            <li><a href="monitoreo.html" class="nav-link">Monitoreo</a></li>
            <li><a href="perfil.html" class="nav-link">Mi Perfil</a></li> 
            <li><a href="#" id="logout-btn" class="nav-link">Cerrar Sesión</a></li>
        `;
  } else {
    navLinksHTML += `
            <li><a href="login.html" class="nav-link nav-login-btn">Iniciar Sesión</a></li>
        `;
  }

  const headerContent = `
        <div class="logo">
            <a href="index.html">
                <img src="assets/img/logo-ecolens.png" alt="EcoLens Logo">
            </a>
        </div>
        <button class="nav-toggle" aria-label="toggle navigation menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
        <nav class="main-nav">
            <ul>${navLinksHTML}</ul>
        </nav>
    `;

  const footerContent = `
        <div class="footer-content">
            <div class="footer-top-row">
                <div class="footer-column">
                    <h4>Links de Interés</h4>
                    <div class="footer-links">
                        <a href="blog.html" class="footer-link">Blog</a>
                        <span class="divider">|</span>
                        <a href="noticias.html" class="footer-link">Noticias</a>
                        <span class="divider">|</span>
                        <a href="terminos.html" class="footer-link">Términos y Condiciones</a>
                    </div>
                </div>

                <div class="footer-column">
                    <h4>Síguenos en nuestras redes</h4>
                    <div class="footer-social">
                        <a href="#" class="social-icon" aria-label="Instagram">📸</a>
                        <a href="#" class="social-icon" aria-label="X">🐦</a>
                        <a href="#" class="social-icon" aria-label="Facebook">👍</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>2025 – <strong>EcoLens</strong></p>
            </div>
        </div>
    `;

  // Cargar dinámicamente header y footer
  const headerElement = document.getElementById("main-header");
  const footerElement = document.getElementById("main-footer");

  if (headerElement) {
    headerElement.innerHTML = headerContent;
    headerElement.classList.add("main-header");

    const navToggle = document.querySelector(".nav-toggle");
    const mainNav = document.querySelector(".main-nav");
    navToggle.addEventListener("click", () => {
      mainNav.classList.toggle("is-active");
      navToggle.classList.toggle("is-active");
    });

    const logoutBtn = document.getElementById("logout-btn");
    if (logoutBtn) {
      logoutBtn.addEventListener("click", (e) => {
        e.preventDefault();
        logout();
      });
    }
  }

  if (footerElement) {
    footerElement.innerHTML = footerContent;
    footerElement.classList.add("main-footer");
  }

  // Activar link activo
  const path = window.location.pathname.split("/").pop();
  const navLinks = document.querySelectorAll(".nav-link");
  navLinks.forEach((link) => {
    if (
      link.getAttribute("href") === path &&
      link.classList.contains("nav-link")
    ) {
      link.classList.add("active");
    }
  });

  // ----------------------------------------------------------------------
  // LOGIN Y REGISTRO
  // ----------------------------------------------------------------------
  const loginForm = document.getElementById("login-form");
  const registerForm = document.getElementById("register-form");

  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      e.preventDefault();
      login();
      window.location.href = "detecciones.html";
    });
  }

  if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
      e.preventDefault();
      alert("¡Registro exitoso! Ya puedes iniciar sesión con tu nueva cuenta.");
      window.location.href = "login.html";
    });
  }

  // ----------------------------------------------------------------------
  // DETECTAR.HTML — Subida de imagen + Geoposición con lógica Pokéfauna
  // ----------------------------------------------------------------------

  const imageUpload = document.getElementById("image-upload");
  const imagePreview = document.getElementById("image-preview");
  const previewImage = document.getElementById("preview-image");
  const detectButton = document.getElementById("detect-button");
  const fileNameDisplay = document.getElementById("file-name");

  const getLocationButton = document.getElementById("get-location-button");
  const geoCoordsInput = document.getElementById("geocoords");
  const locationStatus = document.getElementById("location-status");
  const locationFriendlyInput = document.getElementById("location-friendly");
  const mapContainer = document.getElementById("map-container"); // Nuevo
  let map; // Variable para el mapa (Nuevo)

  if (imageUpload) {
    // Vista previa imagen
    imageUpload.addEventListener("change", function (event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          previewImage.src = e.target.result;
          imagePreview.style.display = "block";
          detectButton.style.display = "inline-block";
          fileNameDisplay.textContent = file.name;
        };
        reader.readAsDataURL(file);
      } else {
        imagePreview.style.display = "none";
        detectButton.style.display = "none";
        fileNameDisplay.textContent = "";
      }
    });

    // 🌍 Obtener ubicación con Nominatim (lógica Pokéfauna mejorada)
    if (getLocationButton) {
      const setButtonState = (text, isDisabled) => {
        getLocationButton.innerHTML = `📍 ${text}`;
        if (isDisabled) {
          getLocationButton.disabled = true;
          getLocationButton.style.opacity = "0.7";
        } else {
          getLocationButton.disabled = false;
          getLocationButton.style.opacity = "1";
        }
      };

      const initMap = (lat, lon) => {
        mapContainer.style.display = "block";
        if (map) {
          map.remove(); // Elimina el mapa anterior si existe
        }
        map = L.map("map-container").setView([lat, lon], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);
        L.marker([lat, lon])
          .addTo(map)
          .bindPopup("Ubicación aproximada.")
          .openPopup();
      };

      getLocationButton.addEventListener("click", async (e) => {
        e.preventDefault();

        setButtonState("Buscando ubicación...", true);
        locationStatus.textContent = "Buscando ubicación...";
        geoCoordsInput.value = "";
        locationFriendlyInput.value = "";
        mapContainer.style.display = "none"; // Oculta el mapa al buscar

        if (!navigator.geolocation) {
          locationStatus.textContent =
            "Geolocalización no soportada por este navegador.";
          geoCoordsInput.value = "N/A";
          locationFriendlyInput.value = "N/A";
          setButtonState("Obtener mi ubicación", false);
          return;
        }

        navigator.geolocation.getCurrentPosition(
          async (position) => {
            const lat = position.coords.latitude.toFixed(6);
            const lon = position.coords.longitude.toFixed(6);
            geoCoordsInput.value = `Lat: ${lat}, Lon: ${lon}`;

            // Inicializa el mapa (Nuevo)
            initMap(lat, lon);

            try {
              const res = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`
              );
              const data = await res.json();

              if (data && data.display_name) {
                locationFriendlyInput.value = data.display_name;
                locationStatus.textContent = "Ubicación capturada con éxito.";
              } else if (data.address) {
                const a = data.address;
                const fallback = [
                  a.road,
                  a.neighbourhood,
                  a.city,
                  a.town,
                  a.village,
                  a.state,
                  a.country,
                ]
                  .filter(Boolean)
                  .join(", ");
                locationFriendlyInput.value =
                  fallback || "Ubicación desconocida.";
                locationStatus.textContent = "Ubicación parcial capturada.";
              } else {
                locationFriendlyInput.value = "No se pudo obtener dirección.";
                locationStatus.textContent = "Error de datos.";
              }

              setButtonState("Ubicación capturada", true);
            } catch (err) {
              console.error("Error al consultar Nominatim:", err);
              locationFriendlyInput.value = `Lat: ${lat}, Lon: ${lon}`;
              locationStatus.textContent =
                "Ubicación obtenida, sin nombre (offline o CORS).";
              setButtonState("Ubicación capturada", true);
            }
          },
          (error) => {
            let message = "Error desconocido al obtener la ubicación.";
            switch (error.code) {
              case error.PERMISSION_DENIED:
                message = "Permiso denegado por el usuario.";
                break;
              case error.POSITION_UNAVAILABLE:
                message = "Ubicación no disponible.";
                break;
              case error.TIMEOUT:
                message = "Tiempo de espera agotado.";
                break;
            }
            locationStatus.textContent = message;
            geoCoordsInput.value = "N/A";
            locationFriendlyInput.value = "N/A";
            setButtonState("Obtener mi ubicación", false);
          },
          { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
      });
    }
  }
});
