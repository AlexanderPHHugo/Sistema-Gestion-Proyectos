<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu &mdash; TecnoSoluciones</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/sidebar.js" defer></script>
</head>
<body>
<?php $moduloActivo = "menu"; ?>

<div class="app-layout">

    <?php require_once "views/Partials/sidebar.php"; ?>

    <div class="app-content">

        <div class="app-topbar">
            <button class="hamburger-button" id="hamburgerButton" type="button" aria-label="Abrir menu">
                <span></span><span></span><span></span>
            </button>
            <h1>Panel principal</h1>
        </div>

        <div class="dashboard-panel">
            <p>Bienvenido, <strong><?php echo h($_SESSION["usuario"]["nombre"]); ?></strong>.
               Selecciona un modulo para comenzar.</p>
        </div>

        <div class="menu-grid">

            <a class="menu-option" href="index.php?accion=clientes">
                <span>Clientes</span>
                <small>Registrar, editar y visualizar clientes.</small>
            </a>

            <a class="menu-option" href="index.php?accion=proyectos">
                <span>Proyectos</span>
                <small>Gestionar, editar y visualizar proyectos.</small>
            </a>

            <?php if (esAdmin()): ?>
                <a class="menu-option" href="index.php?accion=usuarios">
                    <span>Usuarios</span>
                    <small>Gestionar, editar y visualizar usuarios del sistema.</small>
                </a>
            <?php endif; ?>


        </div>

    </div>
</div>

</body>
</html>
