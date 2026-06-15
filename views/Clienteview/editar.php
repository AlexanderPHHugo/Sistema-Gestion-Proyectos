<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar cliente &mdash; TecnoSoluciones</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
            <h1>Editar cliente</h1>
        </div>

        <main class="container">

            <div class="page-header">
                <h1>Editar cliente</h1>
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

            <form class="form-card" action="index.php?accion=cliente_actualizar" method="POST">

                <input type="hidden" name="id" value="<?php echo h($datos["id"] ?? ""); ?>">

                <div class="form-group">
                    <label for="razon_social">Razon social:</label>
                    <input
                        type="text"
                        id="razon_social"
                        name="razon_social"
                        value="<?php echo h($datos["razon_social"] ?? ""); ?>"
                        required
                        maxlength="120">
                </div>

                <div class="form-group">
                    <label for="ruc">RUC:</label>
                    <input
                        type="text"
                        id="ruc"
                        name="ruc"
                        value="<?php echo h($datos["ruc"] ?? ""); ?>"
                        required
                        minlength="11"
                        maxlength="11"
                        pattern="[0-9]{11}"
                        title="Ingrese exactamente 11 digitos">
                </div>

                <div class="form-group">
                    <label for="telefono">Telefono (opcional):</label>
                    <input
                        type="text"
                        id="telefono"
                        name="telefono"
                        value="<?php echo h($datos["telefono"] ?? ""); ?>"
                        maxlength="15">
                </div>

                <div class="form-group">
                    <label for="correo">Correo electronico (opcional):</label>
                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        value="<?php echo h($datos["correo"] ?? ""); ?>"
                        maxlength="100">
                </div>

                <div class="form-group">
                    <label for="direccion">Direccion (opcional):</label>
                    <input
                        type="text"
                        id="direccion"
                        name="direccion"
                        value="<?php echo h($datos["direccion"] ?? ""); ?>"
                        maxlength="150">
                </div>

                <div class="form-actions">
                    <button class="button" type="submit">Actualizar</button>
                    <a class="button button-secondary" href="index.php?accion=clientes">Cancelar</a>
                </div>

            </form>

        </main>
    </div>
</div>

</body>
</html>
