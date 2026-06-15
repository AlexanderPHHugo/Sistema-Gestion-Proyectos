<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario &mdash; TecnoSoluciones</title>
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
            <h1>Editar usuario</h1>
        </div>

        <main class="container">

            <div class="page-header">
                <h1>Editar usuario</h1>
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
                <form action="index.php?accion=usuario_actualizar" method="POST">

                    <input type="hidden" name="id" value="<?php echo h($datos["id"] ?? ""); ?>">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">Nombre <span class="required">*</span></label>
                            <input type="text" id="nombre" name="nombre" value="<?php echo h($datos["nombre"] ?? ""); ?>" required maxlength="80">
                        </div>

                        <div class="form-group">
                            <label for="ape_pat">Apellido paterno <span class="required">*</span></label>
                            <input type="text" id="ape_pat" name="ape_pat" value="<?php echo h($datos["ape_pat"] ?? ""); ?>" required maxlength="80">
                        </div>

                        <div class="form-group">
                            <label for="ape_mat">Apellido materno <span class="required">*</span></label>
                            <input type="text" id="ape_mat" name="ape_mat" value="<?php echo h($datos["ape_mat"] ?? ""); ?>" required maxlength="80">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="dni">DNI <span class="required">*</span></label>
                            <input type="text" id="dni" name="dni" value="<?php echo h($datos["dni"] ?? ""); ?>" required minlength="8" maxlength="8" pattern="[0-9]{8}">
                        </div>

                        <div class="form-group">
                            <label for="usuario">Usuario <span class="required">*</span></label>
                            <input type="text" id="usuario" name="usuario" value="<?php echo h($datos["usuario"] ?? ""); ?>" required minlength="4" maxlength="50">
                        </div>

                        <div class="form-group">
                            <label for="rol">Rol <span class="required">*</span></label>
                            <select id="rol" name="rol" required>
                                <option value="empleado" <?php echo ($datos["rol"] ?? "") === "empleado" ? "selected" : ""; ?>>Empleado</option>
                                <option value="admin" <?php echo ($datos["rol"] ?? "") === "admin" ? "selected" : ""; ?>>Administrador</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="button" type="submit">Actualizar</button>
                        <a class="button button-secondary" href="index.php?accion=usuarios">Cancelar</a>
                    </div>

                </form>
            </div>

        </main>
    </div>
</div>

</body>
</html>