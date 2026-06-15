<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios &mdash; TecnoSoluciones</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/busqueda.js" defer></script>
    <script src="assets/js/sidebar.js" defer></script>
</head>
<body>
<?php $moduloActivo = "usuarios"; ?>

<div class="app-layout">

    <?php require_once "views/Partials/sidebar.php"; ?>

    <div class="app-content">

        <div class="app-topbar">
            <button class="hamburger-button" id="hamburgerButton" type="button" aria-label="Abrir menu">
                <span></span><span></span><span></span>
            </button>
            <h1>Usuarios</h1>
        </div>

        <main class="container">

            <div class="page-header">
                <h1>Lista de usuarios</h1>
                <a class="button" href="index.php?accion=usuario_crear">
                    Nuevo usuario
                </a>
            </div>

            <?php if (isset($_GET["error"]) && $_GET["error"] === "auto_eliminar"): ?>
                <div class="alert-warning">
                    No puedes eliminar tu propio usuario.
                </div>
            <?php endif; ?>

            <section class="search-panel">
                <div class="search-group">
                    <label for="campoBusqueda">Busqueda:</label>
                    <input type="search" id="campoBusqueda" placeholder="Nombre, usuario o DNI..." autocomplete="off">
                </div>
            </section>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre completo</th>
                            <th>DNI</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBody">
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr data-busqueda="<?php echo h($usuario["nombre"] . " " . $usuario["ape_pat"] . " " . $usuario["usuario"] . " " . $usuario["dni"]); ?>">
                                <td><?php echo h($usuario["id"]); ?></td>
                                <td><?php echo h($usuario["nombre"] . " " . $usuario["ape_pat"] . " " . $usuario["ape_mat"]); ?></td>
                                <td><?php echo h($usuario["dni"]); ?></td>
                                <td><?php echo h($usuario["usuario"]); ?></td>
                                <td>
                                    <span class="badge <?php echo $usuario["rol"] === "admin" ? "badge-completado" : "badge-proceso"; ?>">
                                        <?php echo $usuario["rol"] === "admin" ? "Administrador" : "Empleado"; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a class="action-button action-edit" href="index.php?accion=usuario_editar&id=<?php echo h($usuario["id"]); ?>">Editar</a>
                                        <a class="action-button action-edit" href="index.php?accion=usuario_password&id=<?php echo h($usuario["id"]); ?>">Cambiar contraseña</a>
                                        <?php if ($usuario["id"] != $_SESSION["usuario"]["id"]): ?>
                                            <a class="action-button action-delete" href="index.php?accion=usuario_eliminar&id=<?php echo h($usuario["id"]); ?>" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="empty-row" id="filaSinResultados" hidden>
                            <td colspan="6">No se encontraron usuarios.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination" id="paginacionTabla" hidden>
                <button class="button button-secondary" type="button" id="paginaAnterior">Anterior</button>
                <button class="button button-secondary" type="button" id="paginaSiguiente">Siguiente</button>
            </div>

        </main>
    </div>
</div>

</body>
</html>