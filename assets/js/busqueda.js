/*
 * busqueda.js
 * Filtra filas de una tabla segun el texto ingresado en el buscador.
 * Tambien maneja la paginacion de 10 registros por pagina.
 */

document.addEventListener("DOMContentLoaded", function () {

    var campoBusqueda = document.getElementById("campoBusqueda");
    var tabla         = document.getElementById("tablaBody");
    var filaSinDatos  = document.getElementById("filaSinResultados");
    var paginacion    = document.getElementById("paginacionTabla");
    var btnAnterior   = document.getElementById("paginaAnterior");
    var btnSiguiente  = document.getElementById("paginaSiguiente");

    if (!campoBusqueda || !tabla) return;

    var filas       = Array.from(tabla.querySelectorAll("tr[data-busqueda]"));
    var paginaActual = 1;
    var porPagina    = 10;
    var filasFiltradas = filas.slice();

    function mostrarPagina(pagina) {
        var inicio = (pagina - 1) * porPagina;
        var fin    = inicio + porPagina;

        filas.forEach(function (fila) {
            fila.hidden = true;
        });

        filasFiltradas.slice(inicio, fin).forEach(function (fila) {
            fila.hidden = false;
        });

        var totalPaginas = Math.ceil(filasFiltradas.length / porPagina);

        if (paginacion) {
            paginacion.hidden = totalPaginas <= 1;
        }

        if (btnAnterior) btnAnterior.disabled = pagina <= 1;
        if (btnSiguiente) btnSiguiente.disabled = pagina >= totalPaginas;

        if (filaSinDatos) {
            filaSinDatos.hidden = filasFiltradas.length > 0;
        }
    }

    function filtrar() {
        var texto = campoBusqueda.value.toLowerCase().trim();

        filasFiltradas = filas.filter(function (fila) {
            return fila.dataset.busqueda.toLowerCase().includes(texto);
        });

        paginaActual = 1;
        mostrarPagina(paginaActual);
    }

    campoBusqueda.addEventListener("input", filtrar);

    if (btnAnterior) {
        btnAnterior.addEventListener("click", function () {
            if (paginaActual > 1) {
                paginaActual--;
                mostrarPagina(paginaActual);
            }
        });
    }

    if (btnSiguiente) {
        btnSiguiente.addEventListener("click", function () {
            var totalPaginas = Math.ceil(filasFiltradas.length / porPagina);
            if (paginaActual < totalPaginas) {
                paginaActual++;
                mostrarPagina(paginaActual);
            }
        });
    }

    /* Inicializa mostrando la primera pagina. */
    mostrarPagina(paginaActual);
});
