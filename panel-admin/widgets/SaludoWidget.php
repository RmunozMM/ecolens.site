<?php

namespace app\widgets;

use yii\base\Widget;
use Yii;

/**
 * SaludoWidget que muestra saludo, fecha, temperatura y ciudad actual usando Geoapify + OpenMeteo
 */
class SaludoWidget extends Widget
{
    public function run()
    {
        return <<<HTML
<section class="saludo-widget">
  <div class="saludo-card">
    <div class="saludo-header" id="saludo">Cargando saludo...</div>
    <div class="saludo-detalle" id="detalle-clima">Cargando clima y ciudad...</div>
  </div>
</section>

<style>
.saludo-widget {
  display: flex;
  justify-content: flex-end;
}

.saludo-card {
  border-radius: 12px;
  max-width: 420px;
  width: 100%;
}

.saludo-header {
  font-size: 18px;
  font-weight: 600;
  color: #222;
}

.saludo-detalle {
  font-size: 14px;
  margin-top: 0.5rem;
  color: #555;
  font-style: italic;
}
</style>

<script>
const API_KEY_GEOAPIFY = "6dacdd4af3e2491eb482fec8db6d31fe";

function obtenerSaludo() {
  const hora = new Date().getHours();
  if (hora < 12) return "Buenos días";
  if (hora < 19) return "Buenas tardes";
  return "Buenas noches";
}

function descripcionClima(code) {
  const mapa = {
    0: "despejado ☀️",
    1: "mayormente despejado 🌤️",
    2: "parcialmente nublado ⛅",
    3: "nublado ☁️",
    45: "con neblina 🌫️",
    51: "con llovizna 🌦️",
    61: "lluvioso 🌧️",
    71: "nevado 🌨️",
    80: "con chubascos ⛈️",
    95: "con tormenta eléctrica ⚡"
  };
  return mapa[code] || "con condiciones desconocidas ❓";
}

function mostrarSaludoClima(lat, lon) {
  const saludo = obtenerSaludo();
  document.getElementById("saludo").textContent = saludo;

  const climaURL = "https://api.open-meteo.com/v1/forecast?latitude=" + lat + "&longitude=" + lon + "&current_weather=true";
  const ciudadURL = "https://api.geoapify.com/v1/geocode/reverse?lat=" + lat + "&lon=" + lon + "&lang=es&apiKey=" + API_KEY_GEOAPIFY;

  Promise.all([
    fetch(climaURL).then(function(res) { return res.json(); }),
    fetch(ciudadURL).then(function(res) { return res.json(); })
  ])
  .then(function([climaData, ciudadData]) {
    const clima = climaData.current_weather;
    const temp = clima.temperature;
    const descripcion = descripcionClima(clima.weathercode);

    const props = ciudadData.features && ciudadData.features[0] ? ciudadData.features[0].properties : {};
    const ciudad = props.city || props.county || "tu ubicación";

    const fecha = new Date().toLocaleDateString("es-ES", {
      weekday: "long",
      day: "numeric",
      month: "long"
    });

    const texto = "Hoy es " + fecha + ", hay " + temp + " °C en " + ciudad + " y está " + descripcion + ".";

    document.getElementById("detalle-clima").textContent = texto;
  })
  .catch(function(err) {
    console.error("Error al obtener clima o ciudad:", err);
    document.getElementById("detalle-clima").textContent = "No se pudo obtener el clima.";
  });
}

function obtenerUbicacion() {
  if ("geolocation" in navigator) {
    navigator.geolocation.getCurrentPosition(
      function(pos) {
        const lat = pos.coords.latitude.toFixed(5);
        const lon = pos.coords.longitude.toFixed(5);
        mostrarSaludoClima(lat, lon);
      },
      function() {
        console.warn("Geolocalización denegada. Usando Santiago.");
        mostrarSaludoClima(-33.45, -70.67);
      }
    );
  } else {
    console.warn("Geolocalización no disponible. Usando Santiago.");
    mostrarSaludoClima(-33.45, -70.67);
  }
}

document.addEventListener("DOMContentLoaded", obtenerUbicacion);
</script>
HTML;
    }
}
