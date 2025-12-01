// web/js/clock.js
document.addEventListener('DOMContentLoaded', function() {
  const root = document.getElementById('live-clock');
  if (!root) return;

  const hrs  = root.querySelector('.hours');
  const mins = root.querySelector('.minutes');
  const secs = root.querySelector('.seconds');

  function updateClock() {
    const now = new Date();
    if (hrs)  hrs.textContent  = String(now.getHours()).padStart(2, '0');
    if (mins) mins.textContent = String(now.getMinutes()).padStart(2, '0');
    if (secs) secs.textContent = String(now.getSeconds()).padStart(2, '0');
  }

  // Mostrar inmediatamente y luego cada segundo
  updateClock();
  setInterval(updateClock, 1000);
});
