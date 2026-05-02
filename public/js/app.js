document.addEventListener('DOMContentLoaded', function () {
    // Auto-cerrar alertas después de 5 segundos
    document.querySelectorAll('.alert').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 5000);
    });
});
