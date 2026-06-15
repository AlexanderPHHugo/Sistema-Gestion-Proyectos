<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña &mdash; TecnoSoluciones</title>
    <link rel="stylesheet" href="assets/css/style.css">
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
            <h1>Cambiar contraseña</h1>
        </div>

        <main class="container">

            <div class="page-header">
                <h1>Cambiar contraseña de <?php echo h($datos["nombre"] . " " . $datos["ape_pat"]); ?></h1>
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

            <div class="form-card">
                <form action="index.php?accion=usuario_actualizar_password" method="POST">

                    <input type="hidden" name="id" value="<?php echo h($datos["id"] ?? ""); ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Nueva contraseña <span class="required">*</span></label>
                            <input type="password" id="password" name="password" required minlength="8" placeholder="••••••••">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmacion">Confirmar contraseña <span class="required">*</span></label>
                            <input type="password" id="password_confirmacion" name="password_confirmacion" required minlength="8" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="button" type="submit">Cambiar contraseña</button>
                        <a class="button button-secondary" href="index.php?accion=usuarios">Cancelar</a>
                    </div>

                </form>
            </div>

        </main>
    </div>
</div>

</body>
</html>