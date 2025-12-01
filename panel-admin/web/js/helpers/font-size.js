function cambiarTamanioFuente(factor) {
    let body = document.body;
    let style = window.getComputedStyle(body, null).getPropertyValue('font-size');
    let currentSize = parseFloat(style);
    body.style.fontSize = (currentSize + factor) + "px";
    localStorage.setItem('fontSize', body.style.fontSize);
}

document.addEventListener("DOMContentLoaded", function () {
    let savedFontSize = localStorage.getItem('fontSize');
    if (savedFontSize) {
        document.body.style.fontSize = savedFontSize;
    }
});
