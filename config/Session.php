<?php
/*
 * Session.php
 * Gestiona el inicio de sesion seguro y funciones de proteccion de rutas.
 */
function iniciarSesionSegura()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        "lifetime" => 0,
        "path"     => "/",
        "secure"   => isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on",
        "httponly" => true,
        "samesite" => "Lax",
    ]);

    session_start();
}

function usuarioAutenticado()
{
    return isset($_SESSION["usuario"]);
}

function esAdmin()
{
    return usuarioAutenticado() && isset($_SESSION["usuario"]["rol"]) && $_SESSION["usuario"]["rol"] === "admin";
}

function protegerRuta()
{
    if (!usuarioAutenticado()) {
        header("Location: index.php?accion=login");
        exit;
    }
}

function protegerRutaAdmin()
{
    protegerRuta();
    if (!esAdmin()) {
        header("Location: index.php?accion=menu");
        exit;
    }
}
