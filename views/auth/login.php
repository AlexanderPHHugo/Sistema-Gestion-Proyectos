<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesion &mdash; TecnoSoluciones</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">

            <h1>TecnoSoluciones S.A.</h1>

            <?php if (!empty($errores)): ?>
                <div class="error-box">
                    <ul>
                        <?php foreach ($errores as $error): ?>
                            <li><?php echo h($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="index.php?accion=autenticar" method="POST">

                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        value="<?php echo h($usuario ?? ""); ?>"
                        required
                        autocomplete="username"
                        maxlength="50">
                </div>

                <div class="form-group">
                    <label for="password">Contrasena:</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        maxlength="100">
                </div>

                <button class="button full-button" type="submit">
                    Ingresar
                </button>

            </form>

        </div>
    </div>
</body>
</html>
