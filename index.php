<?php
/*
 * index.php
 * Punto de entrada unico del sistema.
 * Todas las pantallas pasan por aqui mediante la variable "accion" de la URL.
 * Ejemplo: index.php?accion=clientes
 */
require_once "config/Session.php";
require_once "controllers/AuthController.php";
require_once "controllers/ClienteController.php";
require_once "controllers/ProyectoController.php";
require_once "controllers/UsuarioController.php";


/* Inicia la sesion antes de decidir que pantalla mostrar. */
iniciarSesionSegura();

/*
 * Si no llega ninguna accion:
 * - Si el usuario ya inicio sesion, se envia al menu.
 * - Si aun no inicio sesion, se envia al login.
 */
$accion = $_GET["accion"] ?? (usuarioAutenticado() ? "menu" : "login");

/* Estas acciones se pueden usar sin haber iniciado sesion. */
$accionesPublicas = ["login", "autenticar"];

/* Bloquea cualquier intento de entrar a modulos privados sin iniciar sesion. */
if (!in_array($accion, $accionesPublicas) && !usuarioAutenticado()) {
    header("Location: index.php?accion=login");
    exit;
}

$authController    = new AuthController();

/*
 * Enrutador principal del sistema.
 * Segun la accion recibida, llama al metodo correspondiente del controlador.
 */
switch ($accion) {

    /* ── Autenticacion ─────────────────────────────────── */
    case "login":
        if (usuarioAutenticado()) {
            header("Location: index.php?accion=menu");
            exit;
        }
        $authController->login();
        break;

    case "autenticar":
        $authController->autenticar();
        break;

    case "menu":
        $authController->menu();
        break;

    case "logout":
        $authController->logout();
        break;

    /* ── Clientes ──────────────────────────────────────── */
    case "clientes":
        $clienteController = new ClienteController();
        $clienteController->index();
        break;

    case "cliente_crear":
        $clienteController = new ClienteController();
        $clienteController->crear();
        break;

    case "cliente_guardar":
        $clienteController = new ClienteController();
        $clienteController->guardar();
        break;

    case "cliente_editar":
        $clienteController = new ClienteController();
        $clienteController->editar();
        break;

    case "cliente_actualizar":
        $clienteController = new ClienteController();
        $clienteController->actualizar();
        break;

    case "cliente_eliminar":
        $clienteController = new ClienteController();
        $clienteController->eliminar();
        break;

    /* ── Proyectos ─────────────────────────────────────── */
    case "proyectos":
        $proyectoController = new ProyectoController();
        $proyectoController->index();
        break;

    case "proyecto_crear":
        $proyectoController = new ProyectoController();
        $proyectoController->crear();
        break;

    case "proyecto_guardar":
        $proyectoController = new ProyectoController();
        $proyectoController->guardar();
        break;

    case "proyecto_editar":
        $proyectoController = new ProyectoController();
        $proyectoController->editar();
        break;

    case "proyecto_actualizar":
        $proyectoController = new ProyectoController();
        $proyectoController->actualizar();
        break;

    case "proyecto_eliminar":
        $proyectoController = new ProyectoController();
        $proyectoController->eliminar();
        break;

    case "reporte_pdf":
        $proyectoController = new ProyectoController();
        $proyectoController->generarReporte();
        break;
    
    /* ── Usuarios (solo admin) ───────────────────────────── */
    case "usuarios":
        $usuarioController = new UsuarioController();
        $usuarioController->index();
        break;

    case "usuario_crear":
        $usuarioController = new UsuarioController();
        $usuarioController->crear();
        break;

    case "usuario_guardar":
        $usuarioController = new UsuarioController();
        $usuarioController->guardar();
        break;

    case "usuario_editar":
        $usuarioController = new UsuarioController();
        $usuarioController->editar();
        break;

    case "usuario_actualizar":
        $usuarioController = new UsuarioController();
        $usuarioController->actualizar();
        break;

    case "usuario_eliminar":
        $usuarioController = new UsuarioController();
        $usuarioController->eliminar();
        break;

    case "usuario_password":
        $usuarioController = new UsuarioController();
        $usuarioController->cambiarPassword();
        break;

    case "usuario_actualizar_password":
        $usuarioController = new UsuarioController();
        $usuarioController->actualizarPassword();
        break;

    default:
        echo "Accion no encontrada.";
        break;
}
