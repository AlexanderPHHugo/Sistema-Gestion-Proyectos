<?php
/*
 * AuthController.php
 * Controlador encargado del login, registro, menu y logout.
 */
require_once "config/Database.php";
require_once "config/helpers.php";
require_once "config/Session.php";
require_once "models/Usuario.php";

class AuthController
{
    private $modelo;

    public function __construct()
    {
        $database      = new Database();
        $db            = $database->conectar();
        $this->modelo  = new Usuario($db);
    }

    /* Muestra el formulario de inicio de sesion. */
    public function login()
    {
        $errores = [];
        $usuario = "";
        require_once "views/auth/login.php";
    }

    /* Procesa las credenciales enviadas desde el formulario de login. */
    public function autenticar()
    {
        $usuario  = trim($_POST["usuario"] ?? "");
        $password = $_POST["password"] ?? "";
        $errores  = [];

        if ($usuario === "" || $password === "") {
            $errores[] = "Ingrese usuario y contrasena.";
            require_once "views/auth/login.php";
            return;
        }

        $usuarioEncontrado = $this->modelo->obtenerPorUsuario($usuario);

        /*
         * password_verify compara la contrasena ingresada
         * con el hash almacenado en la base de datos.
         */
        if (!$usuarioEncontrado || !password_verify($password, $usuarioEncontrado["password"])) {
            $errores[] = "Usuario o contrasena incorrectos.";
            require_once "views/auth/login.php";
            return;
        }

        /* Regenera el ID de sesion para evitar reutilizacion de sesiones anteriores. */
        session_regenerate_id(true);

        /* Guarda solo los datos necesarios en sesion, nunca la contrasena. */
        $_SESSION["usuario"] = [
            "id"      => $usuarioEncontrado["id"],
            "nombre"  => $usuarioEncontrado["nombre"],
            "usuario" => $usuarioEncontrado["usuario"],
            "rol"     => $usuarioEncontrado["rol"],
        ];

        header("Location: index.php?accion=menu");
        exit;
    }

    /* Muestra el menu principal del sistema. */
    public function menu()
    {
        protegerRuta();
        require_once "views/menu.php";
    }

    /* Cierra la sesion activa y redirige al login. */
    public function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: index.php?accion=login");
        exit;
    }
}
