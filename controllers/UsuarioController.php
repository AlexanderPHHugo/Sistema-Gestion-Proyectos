<?php
/*
 * UsuarioController.php
 * Controlador encargado de las operaciones CRUD de usuarios.
 * Solo accesible por administradores.
 */
require_once "config/Database.php";
require_once "config/helpers.php";
require_once "config/Session.php";
require_once "models/Usuario.php";

class UsuarioController
{
    private $modelo;

    public function __construct()
    {
        $database     = new Database();
        $db           = $database->conectar();
        $this->modelo = new Usuario($db);
    }

    /* Lista todos los usuarios registrados. */
    public function index()
    {
        protegerRutaAdmin();
        $usuarios = $this->modelo->listar();
        require_once "views/Usuarioview/index.php";
    }

    /* Muestra el formulario para registrar un nuevo usuario. */
    public function crear()
    {
        protegerRutaAdmin();
        $errores = [];
        $datos   = [];
        require_once "views/Usuarioview/crear.php";
    }

    /* Procesa el formulario y guarda el nuevo usuario. */
    public function guardar()
    {
        protegerRutaAdmin();
        $datos   = $this->obtenerDatosRegistro();
        $errores = $this->validarRegistro($datos, true);

        if (!empty($errores)) {
            require_once "views/Usuarioview/crear.php";
            return;
        }

        $datos["password"] = password_hash($datos["password"], PASSWORD_DEFAULT);

        try {
            $this->modelo->guardar($datos);
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $errores[] = "El DNI o usuario ya se encuentra registrado.";
            } else {
                $errores[] = "No se pudo registrar el usuario. Intente nuevamente.";
            }
            require_once "views/Usuarioview/crear.php";
            return;
        }

        header("Location: index.php?accion=usuarios");
        exit;
    }

    /* Muestra el formulario de edicion con los datos actuales del usuario. */
    public function editar()
    {
        protegerRutaAdmin();
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        $usuario = $this->modelo->obtenerPorId($id);

        if (!$usuario) {
            echo "Usuario no encontrado.";
            return;
        }

        $errores = [];
        $datos   = $usuario;
        require_once "views/Usuarioview/editar.php";
    }

    /* Procesa los cambios del formulario de edicion. */
    public function actualizar()
    {
        protegerRutaAdmin();
        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        $datos       = $this->obtenerDatosEdicion();
        $datos["id"] = $id;
        $errores     = $this->validarRegistro($datos, false);

        if (!empty($errores)) {
            require_once "views/Usuarioview/editar.php";
            return;
        }

        try {
            $this->modelo->actualizar(
                $id,
                $datos["nombre"],
                $datos["ape_pat"],
                $datos["ape_mat"],
                $datos["dni"],
                $datos["usuario"],
                $datos["rol"]
            );
        } catch (PDOException $e) {
            if ($e->getCode() === "23000") {
                $errores[] = "El DNI o usuario ya se encuentra registrado por otro usuario.";
            } else {
                $errores[] = "No se pudo actualizar el usuario. Intente nuevamente.";
            }
            require_once "views/Usuarioview/editar.php";
            return;
        }

        header("Location: index.php?accion=usuarios");
        exit;
    }

    /* Elimina un usuario por su id. */
    public function eliminar()
    {
        protegerRutaAdmin();
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        /* Evita que el administrador se elimine a si mismo. */
        if ($id == $_SESSION["usuario"]["id"]) {
            header("Location: index.php?accion=usuarios&error=auto_eliminar");
            exit;
        }

        $this->modelo->eliminar($id);
        header("Location: index.php?accion=usuarios");
        exit;
    }

    /* Muestra el formulario para cambiar contraseña. */
    public function cambiarPassword()
    {
        protegerRutaAdmin();
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        $usuario = $this->modelo->obtenerPorId($id);

        if (!$usuario) {
            echo "Usuario no encontrado.";
            return;
        }

        $errores = [];
        $datos   = $usuario;
        require_once "views/Usuarioview/cambiar_password.php";
    }

    /* Procesa el cambio de contraseña. */
    public function actualizarPassword()
    {
        protegerRutaAdmin();
        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        $password        = $_POST["password"] ?? "";
        $passwordConfirm = $_POST["password_confirmacion"] ?? "";
        $errores         = [];

        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/", $password)) {
            $errores[] = "La contrasena debe tener minimo 8 caracteres, mayuscula, minuscula, numero y simbolo.";
        }

        if ($password !== $passwordConfirm) {
            $errores[] = "Las contrasenas no coinciden.";
        }

        if (!empty($errores)) {
            $datos = $this->modelo->obtenerPorId($id);
            require_once "views/Usuarioview/cambiar_password.php";
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->modelo->cambiarPassword($id, $passwordHash);

        header("Location: index.php?accion=usuarios");
        exit;
    }

    private function obtenerDatosRegistro()
    {
        return [
            "nombre"                => trim($_POST["nombre"] ?? ""),
            "ape_pat"               => trim($_POST["ape_pat"] ?? ""),
            "ape_mat"               => trim($_POST["ape_mat"] ?? ""),
            "dni"                   => trim($_POST["dni"] ?? ""),
            "usuario"               => trim($_POST["usuario"] ?? ""),
            "password"              => $_POST["password"] ?? "",
            "password_confirmacion" => $_POST["password_confirmacion"] ?? "",
            "rol"                   => trim($_POST["rol"] ?? "empleado"),
        ];
    }

    private function obtenerDatosEdicion()
    {
        return [
            "nombre"                => trim($_POST["nombre"] ?? ""),
            "ape_pat"               => trim($_POST["ape_pat"] ?? ""),
            "ape_mat"               => trim($_POST["ape_mat"] ?? ""),
            "dni"                   => trim($_POST["dni"] ?? ""),
            "usuario"               => trim($_POST["usuario"] ?? ""),
            "rol"                   => trim($_POST["rol"] ?? "empleado"),
        ];
    }

    private function validarRegistro($datos, $validarPassword = true)
    {
        $errores = [];

        $this->validarNombre($errores, $datos["nombre"], "El nombre");
        $this->validarNombre($errores, $datos["ape_pat"], "El apellido paterno");
        $this->validarNombre($errores, $datos["ape_mat"], "El apellido materno");

        if (!preg_match("/^[0-9]{8}$/", $datos["dni"])) {
            $errores[] = "El DNI debe tener exactamente 8 digitos.";
        }

        if (!preg_match("/^[A-Za-z0-9_]{4,50}$/", $datos["usuario"])) {
            $errores[] = "El usuario debe tener de 4 a 50 caracteres (letras, numeros o guion bajo).";
        }

        if ($validarPassword) {
            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/", $datos["password"])) {
                $errores[] = "La contrasena debe tener minimo 8 caracteres, mayuscula, minuscula, numero y simbolo.";
            }

            if ($datos["password"] !== $datos["password_confirmacion"]) {
                $errores[] = "Las contrasenas no coinciden.";
            }
        }

        $rolesValidos = ["admin", "empleado"];
        if (!in_array($datos["rol"], $rolesValidos)) {
            $errores[] = "Seleccione un rol valido.";
        }

        return $errores;
    }

    private function validarNombre(&$errores, $valor, $campo)
    {
        if ($valor === "") {
            $errores[] = $campo . " es obligatorio.";
            return;
        }
        if (strlen($valor) > 80) {
            $errores[] = $campo . " no debe superar 80 caracteres.";
        }
        if (!preg_match("/^[\p{L} ]+$/u", $valor)) {
            $errores[] = $campo . " solo debe contener letras y espacios.";
        }
    }
}