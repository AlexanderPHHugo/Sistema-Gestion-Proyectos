<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar proyecto &mdash; TecnoSoluciones</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
            <h1>Editar proyecto</h1>
        </div>

        <main class="container">

            <div class="page-header">
                <h1>Editar proyecto</h1>
            </div>

            <?php if (!empty($errores)): ?>
                <div class="error-box">
                    <strong>Corrige los siguientes errores:</strong>
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo h($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form class="form-card" action="index.php?accion=proyecto_actualizar" method="POST">

                <input type="hidden" name="id" value="<?php echo h($datos["id"] ?? ""); ?>">

                <div class="form-group">
                    <label for="cliente_id">Cliente:</label>
                    <select id="cliente_id" name="cliente_id" required>
                        <option value="">-- Seleccione un cliente --</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option
                                value="<?php echo h($cliente["id"]); ?>"
                                <?php echo (isset($datos["cliente_id"]) && $datos["cliente_id"] == $cliente["id"]) ? "selected" : ""; ?>>
                                <?php echo h($cliente["razon_social"]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre del proyecto:</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="<?php echo h($datos["nombre"] ?? ""); ?>"
                        required
                        maxlength="120">
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripcion (opcional):</label>
                    <textarea id="descripcion" name="descripcion"><?php echo h($datos["descripcion"] ?? ""); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <select id="estado" name="estado" required>
                        <?php
                        $estados = ["En proceso", "Completado", "Pausado"];
                        foreach ($estados as $est):
                        ?>
                            <option
                                value="<?php echo h($est); ?>"
                                <?php echo (isset($datos["estado"]) && $datos["estado"] === $est) ? "selected" : ""; ?>>
                                <?php echo h($est); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_inicio">Fecha de inicio:</label>
                    <input
                        type="date"
                        id="fecha_inicio"
                        name="fecha_inicio"
                        value="<?php echo h($datos["fecha_inicio"] ?? ""); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="fecha_fin">Fecha de fin (opcional):</label>
                    <input
                        type="date"
                        id="fecha_fin"
                        name="fecha_fin"
                        value="<?php echo h($datos["fecha_fin"] ?? ""); ?>">
                </div>

                <div class="form-actions">
                    <button class="button" type="submit">Actualizar</button>
                    <a class="button button-secondary" href="index.php?accion=proyectos">Cancelar</a>
                </div>

            </form>

        </main>
    </div>
</div>

</body>
</html>
