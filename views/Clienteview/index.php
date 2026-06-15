<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes &mdash; TecnoSoluciones</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/busqueda.js" defer></script>
    <script src="assets/js/sidebar.js" defer></script>
</head>
<body>
<?php $moduloActivo = "clientes"; ?>

<div class="app-layout">

    <?php require_once "views/Partials/sidebar.php"; ?>

    <div class="app-content">

        <div class="app-topbar">
            <button class="hamburger-button" id="hamburgerButton" type="button" aria-label="Abrir menu">
                <span></span><span></span><span></span>
            </button>
            <h1>Clientes</h1>
        </div>

        <main class="container">

            <div class="page-header">
                <h1>Lista de clientes</h1>
                <a class="button" href="index.php?accion=cliente_crear">
                    Nuevo cliente
                </a>
            </div>

            <?php if (isset($_GET["error"]) && $_GET["error"] === "tiene_proyectos"): ?>
                <div class="alert-warning">
                    No se puede eliminar este cliente porque tiene proyectos asociados.
                    Elimine primero los proyectos del cliente.
                </div>
            <?php endif; ?>

            <section class="search-panel">
                <div class="search-group">
                    <label for="campoBusqueda">Busqueda:</label>
                    <input
                        type="search"
                        id="campoBusqueda"
                        placeholder="Razon social o RUC..."
                        autocomplete="off">
                </div>
            </section>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Razon social</th>
                            <th>RUC</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Direccion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBody">
                        <?php foreach ($clientes as $cliente): ?>
                            <tr data-busqueda="<?php echo h($cliente["razon_social"] . " " . $cliente["ruc"]); ?>">
                                <td><?php echo h($cliente["id"]); ?></td>
                                <td><?php echo h($cliente["razon_social"]); ?></td>
                                <td><?php echo h($cliente["ruc"]); ?></td>
                                <td><?php echo h($cliente["telefono"] ?? "—"); ?></td>
                                <td><?php echo h($cliente["correo"] ?? "—"); ?></td>
                                <td><?php echo h($cliente["direccion"] ?? "—"); ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="action-button action-edit"
                                           href="index.php?accion=cliente_editar&id=<?php echo h($cliente["id"]); ?>">
                                            Editar
                                        </a>
                                        <a class="action-button action-delete"
                                           href="index.php?accion=cliente_eliminar&id=<?php echo h($cliente["id"]); ?>"
                                           onclick="return confirm('¿Eliminar este cliente?')">
                                            Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="empty-row" id="filaSinResultados" hidden>
                            <td colspan="7">No se encontraron clientes.</td>
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
