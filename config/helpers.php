<?php
/*
 * helpers.php
 * Funciones utilitarias reutilizables en todo el sistema.
 */

/*
 * h() sanitiza valores antes de mostrarlos en HTML.
 * Evita que caracteres especiales se interpreten como codigo HTML.
 */
function h($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}
