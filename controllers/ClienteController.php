<?php
/*
 * ClienteController.php
 * Controlador encargado de las operaciones CRUD de clientes.
 */
require_once "config/Database.php";
require_once "config/helpers.php";
require_once "config/Session.php";
require_once "models/Cliente.php";

class ClienteController
{
    private $modelo;

    public function __construct()
    {
        $database     = new Database();
        $db           = $database->conectar();
        $this->modelo = new Cliente($db);
    }

    /* Lista todos los clientes registrados. */
    public function index()
    {
        protegerRuta();
        $clientes = $this->modelo->listar();
        require_once "views/Clienteview/index.php";
    }

    /* Muestra el formulario para registrar un nuevo cliente. */
    public function crear()
    {
        protegerRuta();
        $errores = [];
        $datos   = [];
        require_once "views/Clienteview/crear.php";
    }

    /* Procesa el formulario y guarda el nuevo cliente. */
    public function guardar()
    {
        protegerRuta();
        $datos   = $this->obtenerDatosFormulario();
        $errores = $this->validarCliente($datos);

        if (!empty($errores)) {
            require_once "views/Clienteview/crear.php";
            return;
        }

        try {
            $this->modelo->guardar(
                $datos["razon_social"],
                $datos["ruc"],
                $datos["telefono"],
                $this->valorOpcional($datos["correo"]),
                $this->valorOpcional($datos["direccion"])
            );
        } catch (PDOException $e) {
            $errores[] = $this->mensajeError($e);
            require_once "views/Clienteview/crear.php";
            return;
        }

        header("Location: index.php?accion=clientes");
        exit;
    }

    /* Muestra el formulario de edicion con los datos actuales del cliente. */
    public function editar()
    {
        protegerRuta();
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        $cliente = $this->modelo->obtenerPorId($id);

        if (!$cliente) {
            echo "Cliente no encontrado.";
            return;
        }

        $errores = [];
        $datos   = $cliente;
        require_once "views/Clienteview/editar.php";
    }

    /* Procesa los cambios del formulario de edicion. */
    public function actualizar()
    {
        protegerRuta();
        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        $datos       = $this->obtenerDatosFormulario();
        $datos["id"] = $id;
        $errores     = $this->validarCliente($datos);

        if (!empty($errores)) {
            require_once "views/Clienteview/editar.php";
            return;
        }

        try {
            $this->modelo->actualizar(
                $id,
                $datos["razon_social"],
                $datos["ruc"],
                $datos["telefono"],
                $this->valorOpcional($datos["correo"]),
                $this->valorOpcional($datos["direccion"])
            );
        } catch (PDOException $e) {
            $errores[] = $this->mensajeError($e);
            require_once "views/Clienteview/editar.php";
            return;
        }

        header("Location: index.php?accion=clientes");
        exit;
    }

    /* Elimina un cliente por su id. */
    public function eliminar()
    {
        protegerRuta();
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        try {
            $this->modelo->eliminar($id);
        } catch (PDOException $e) {
            /*
             * Si el cliente tiene proyectos asociados,
             * MySQL lanzara un error de integridad referencial.
             */
            header("Location: index.php?accion=clientes&error=tiene_proyectos");
            exit;
        }

        header("Location: index.php?accion=clientes");
        exit;
    }

    private function obtenerDatosFormulario()
    {
        return [
            "razon_social" => trim($_POST["razon_social"] ?? ""),
            "ruc"          => trim($_POST["ruc"] ?? ""),
            "telefono"     => trim($_POST["telefono"] ?? ""),
            "correo"       => trim($_POST["correo"] ?? ""),
            "direccion"    => trim($_POST["direccion"] ?? ""),
        ];
    }

    private function validarCliente($datos)
    {
        $errores = [];

        $razonSocial = trim($datos["razon_social"]);

        if ($razonSocial === '') {
            $errores[] = "La razón social es obligatoria.";
        }       
        elseif (mb_strlen($razonSocial) < 3) {
            $errores[] = "La razón social debe tener al menos 3 caracteres.";
        }
        elseif (mb_strlen($razonSocial) > 120) {
            $errores[] = "La razón social no debe superar los 120 caracteres.";
        }
// Solo letras, números, espacios, puntos, comas, &, guiones y apostrofes
        elseif (!preg_match('/^[\p{L}\p{N}\s.,&\'-]+$/u', $razonSocial)) {
            $errores[] = "La razón social contiene caracteres no permitidos.";
        }
// Debe comenzar con una letra o número
        elseif (!preg_match('/^[\p{L}\p{N}]/u', $razonSocial)) {
            $errores[] = "La razón social debe comenzar con una letra o número.";
        }
// Debe contener al menos una letra
        elseif (!preg_match('/\p{L}/u', $razonSocial)) {
            $errores[] = "La razón social debe contener al menos una letra.";
        }

        if (!preg_match("/^[0-9]{11}$/", $datos["ruc"])) {
            $errores[] = "El RUC debe tener exactamente 11 digitos.";
        }

        if ($datos["telefono"] === "") {
            $errores[] = "El numero de telefono es obligatorio.";
        } elseif (!preg_match("/^[0-9]{6,15}$/", $datos["telefono"])) {
            $errores[] = "El telefono debe tener entre 6 y 15 digitos.";
        }

        if ($datos["correo"] !== "" && !filter_var($datos["correo"], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo electronico no tiene un formato valido.";
        }

        if ($datos["direccion"] !== "" && strlen($datos["direccion"]) > 150) {
            $errores[] = "La direccion no debe superar 150 caracteres.";
        }

        return $errores;
    }

    private function valorOpcional($valor)
    {
        return $valor === "" ? null : $valor;
    }

    private function mensajeError($e)
    {
        if ($e->getCode() === "23000") {
            return "Ya existe un cliente registrado con ese RUC.";
        }
        return "No se pudo guardar la informacion. Intente nuevamente.";
    }
}
