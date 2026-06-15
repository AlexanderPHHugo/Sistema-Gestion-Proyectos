<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos &mdash; TecnoSoluciones</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/busqueda.js" defer></script>
    <script src="assets/js/sidebar.js" defer></script>
</head>
<body>
<?php $moduloActivo = "proyectos"; ?>

<div class="app-layout">

    <?php require_once "views/Partials/sidebar.php"; ?>

    <div class="app-content">

        <div class="app-topbar">
            <button class="hamburger-button" id="hamburgerButton" type="button" aria-label="Abrir menu">
                <span></span><span></span><span></span>
            </button>
            <h1>Proyectos</h1>
        </div>

        <main class="container">

            <div class="page-header">
                <h1>Lista de proyectos</h1>
                <div class="actions">
                    <a class="button button-success" href="index.php?accion=reporte_pdf">
                        Generar PDF
                    </a>
                    <a class="button" href="index.php?accion=proyecto_crear">
                        Nuevo proyecto
                    </a>
                </div>
            </div>

            <?php if (isset($_GET["error"]) && $_GET["error"] === "sin_datos"): ?>
                <div class="alert-warning">
                    No hay proyectos registrados para generar el reporte.
                </div>
            <?php endif; ?>

            <section class="search-panel">
                <div class="search-group">
                    <label for="campoBusqueda">Busqueda:</label>
                    <input
                        type="search"
                        id="campoBusqueda"
                        placeholder="Nombre del proyecto o cliente..."
                        autocomplete="off">
                </div>
            </section>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Proyecto</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Fecha inicio</th>
                            <th>Fecha fin</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBody">
                        <?php foreach ($proyectos as $proyecto): ?>
                            <?php
                            $badgeClase = "badge-proceso";
                            if ($proyecto["estado"] === "Completado") $badgeClase = "badge-completado";
                            if ($proyecto["estado"] === "Pausado")    $badgeClase = "badge-pausado";
                            ?>
                            <tr data-busqueda="<?php echo h($proyecto["nombre"] . " " . $proyecto["cliente_nombre"]); ?>">
                                <td><?php echo h($proyecto["id"]); ?></td>
                                <td><?php echo h($proyecto["nombre"]); ?></td>
                                <td><?php echo h($proyecto["cliente_nombre"]); ?></td>
                                <td>
                                    <span class="badge <?php echo $badgeClase; ?>">
                                        <?php echo h($proyecto["estado"]); ?>
                                    </span>
                                </td>
                                <td><?php echo h($proyecto["fecha_inicio"]); ?></td>
                                <td><?php echo h($proyecto["fecha_fin"] ?? "—"); ?></td>
                                <td>
                                    <div class="actions">
                                        <a class="action-button action-edit"
                                           href="index.php?accion=proyecto_editar&id=<?php echo h($proyecto["id"]); ?>">
                                            Editar
                                        </a>
                                        <a class="action-button action-delete"
                                           href="index.php?accion=proyecto_eliminar&id=<?php echo h($proyecto["id"]); ?>"
                                           onclick="return confirm('¿Eliminar este proyecto?')">
                                            Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="empty-row" id="filaSinResultados" hidden>
                            <td colspan="7">No se encontraron proyectos.</td>
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
