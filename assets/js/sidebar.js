/*
 * sidebar.js
 * Maneja la apertura y cierre del menu lateral en pantallas pequeñas (movil)
 * y el colapso del menu en pantallas grandes (escritorio).
 */

document.addEventListener("DOMContentLoaded", function () {

    var boton   = document.getElementById("hamburgerButton");
    var sidebar = document.querySelector(".app-sidebar");
    var layout  = document.querySelector(".app-layout");

    if (!boton || !sidebar || !layout) return;

    boton.addEventListener("click", function (e) {
        e.stopPropagation();
        if (window.innerWidth > 768) {
            // Escritorio: colapsar lateralmente
            layout.classList.toggle("sidebar-collapsed");
        } else {
            // Movil: abrir/cerrar cajon flotante
            sidebar.classList.toggle("open");
        }
    });

    /* Cierra el sidebar si el usuario hace clic fuera de el (solo en movil). */
    document.addEventListener("click", function (e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !boton.contains(e.target)) {
                sidebar.classList.remove("open");
            }
        }
    });

    /* Limpia clases al cambiar el tamaño de pantalla para evitar estados inconsistentes. */
    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) {
            sidebar.classList.remove("open");
        } else {
            layout.classList.remove("sidebar-collapsed");
        }
    });
});
