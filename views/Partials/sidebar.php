<?php
/*
 * sidebar.php
 * Menu lateral reutilizado en todas las vistas del sistema.
 * La variable $moduloActivo viene definida en cada vista
 * para resaltar el enlace correspondiente.
 */
$moduloActivo = $moduloActivo ?? "";
?>

<aside class="app-sidebar" id="appSidebar">

    <div class="sidebar-header">
        <strong>TecnoSoluciones S.A.</strong>
        <small><?php echo h($_SESSION["usuario"]["nombre"]); ?></small>
    </div>

    <nav class="sidebar-nav">
        <a class="<?php echo $moduloActivo === "menu" ? "active" : ""; ?>"
           href="index.php?accion=menu">
            Inicio
        </a>
        <a class="<?php echo $moduloActivo === "clientes" ? "active" : ""; ?>"
           href="index.php?accion=clientes">
            Clientes
        </a>
        <a class="<?php echo $moduloActivo === "proyectos" ? "active" : ""; ?>"
           href="index.php?accion=proyectos">
            Proyectos
        </a>
        <?php if (esAdmin()): ?>
        <a class="<?php echo $moduloActivo === "usuarios" ? "active" : ""; ?>"
           href="index.php?accion=usuarios">
            Usuarios
        </a>
    <?php endif; ?>

    </nav>

    <div class="sidebar-footer">
        <span>Rol: <?php echo h($_SESSION["usuario"]["rol"]); ?></span>
        <a href="index.php?accion=logout">Cerrar sesion</a>
    </div>

</aside>
