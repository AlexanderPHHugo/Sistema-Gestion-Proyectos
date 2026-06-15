<?php
/*
 * ProyectoController.php
 * Controlador encargado de las operaciones CRUD de proyectos
 * y de la generacion del reporte PDF con DOMPDF.
 */
require_once "config/Database.php";
require_once "config/helpers.php";
require_once "config/Session.php";
require_once "models/Proyecto.php";
require_once "models/Cliente.php";

use Dompdf\Dompdf;

class ProyectoController
{
    private $modelo;
    private $modeloCliente;

    public function __construct()
    {
        $database            = new Database();
        $db                  = $database->conectar();
        $this->modelo        = new Proyecto($db);
        $this->modeloCliente = new Cliente($db);
    }

    /* Lista todos los proyectos con el nombre del cliente asociado. */
    public function index()
    {
        protegerRuta();
        $proyectos = $this->modelo->listar();
        require_once "views/Proyectoview/index.php";
    }

    /* Muestra el formulario para registrar un nuevo proyecto. */
    public function crear()
    {
        protegerRuta();
        $errores  = [];
        $datos    = [];
        $clientes = $this->modeloCliente->listar();
        require_once "views/Proyectoview/crear.php";
    }

    /* Procesa el formulario y guarda el nuevo proyecto. */
    public function guardar()
    {
        protegerRuta();
        $datos    = $this->obtenerDatosFormulario();
        $errores  = $this->validarProyecto($datos);
        $clientes = $this->modeloCliente->listar();

        if (!empty($errores)) {
            require_once "views/Proyectoview/crear.php";
            return;
        }

        try {
            $this->modelo->guardar(
                (int) $datos["cliente_id"],
                $datos["nombre"],
                $this->valorOpcional($datos["descripcion"]),
                $datos["estado"],
                $datos["fecha_inicio"],
                $this->valorOpcional($datos["fecha_fin"])
            );
        } catch (PDOException $e) {
            $errores[] = "No se pudo guardar el proyecto. Intente nuevamente.";
            require_once "views/Proyectoview/crear.php";
            return;
        }

        header("Location: index.php?accion=proyectos");
        exit;
    }

    /* Muestra el formulario de edicion con los datos actuales del proyecto. */
    public function editar()
    {
        protegerRuta();
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        $proyecto = $this->modelo->obtenerPorId($id);

        if (!$proyecto) {
            echo "Proyecto no encontrado.";
            return;
        }

        $clientes = $this->modeloCliente->listar();
        $errores  = [];
        $datos    = $proyecto;
        require_once "views/Proyectoview/editar.php";
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
        $clientes    = $this->modeloCliente->listar();
        $errores     = $this->validarProyecto($datos);

        if (!empty($errores)) {
            require_once "views/Proyectoview/editar.php";
            return;
        }

        try {
            $this->modelo->actualizar(
                $id,
                (int) $datos["cliente_id"],
                $datos["nombre"],
                $this->valorOpcional($datos["descripcion"]),
                $datos["estado"],
                $datos["fecha_inicio"],
                $this->valorOpcional($datos["fecha_fin"])
            );
        } catch (PDOException $e) {
            $errores[] = "No se pudo actualizar el proyecto. Intente nuevamente.";
            require_once "views/Proyectoview/editar.php";
            return;
        }

        header("Location: index.php?accion=proyectos");
        exit;
    }

    /* Elimina un proyecto por su id. */
    public function eliminar()
    {
        protegerRuta();
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (!$id) {
            echo "ID no valido.";
            return;
        }

        $this->modelo->eliminar($id);
        header("Location: index.php?accion=proyectos");
        exit;
    }

    /*
     * Genera el reporte PDF de proyectos usando DOMPDF.
     * Obtiene los datos del modelo, construye el HTML
     * y lo convierte a PDF para descarga en el navegador.
     */
    public function generarReporte()
    {
        protegerRuta();

        $proyectos = $this->modelo->obtenerDatosReporte();

        if (empty($proyectos)) {
            header("Location: index.php?accion=proyectos&error=sin_datos");
            exit;
        }

        require_once "vendor/autoload.php";

        $fechaReporte = date("d/m/Y H:i");

        ob_start();
        require_once "templates/reporte/reporte_pdf.php";
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper("A4", "landscape");
        $dompdf->render();
        $dompdf->stream("reporte_proyectos_" . date("Ymd") . ".pdf", ["Attachment" => true]);
        exit;
    }

    private function obtenerDatosFormulario()
    {
        return [
            "cliente_id"  => trim($_POST["cliente_id"] ?? ""),
            "nombre"      => trim($_POST["nombre"] ?? ""),
            "descripcion" => trim($_POST["descripcion"] ?? ""),
            "estado"      => trim($_POST["estado"] ?? ""),
            "fecha_inicio"=> trim($_POST["fecha_inicio"] ?? ""),
            "fecha_fin"   => trim($_POST["fecha_fin"] ?? ""),
        ];
    }

    private function validarProyecto($datos)
    {
        $errores = [];

        if (!filter_var($datos["cliente_id"], FILTER_VALIDATE_INT)) {
            $errores[] = "Debe seleccionar un cliente.";
        }

        if ($datos["nombre"] === "") {
            $errores[] = "El nombre del proyecto es obligatorio.";
        } elseif (strlen($datos["nombre"]) <= 3) {
            $errores[] = "El nombre del proyecto debe tener mas de 3 caracteres.";
        } elseif (strlen($datos["nombre"]) > 120) {
            $errores[] = "El nombre no debe superar 120 caracteres.";
        }

        $estadosValidos = ["En proceso", "Completado", "Pausado"];
        if (!in_array($datos["estado"], $estadosValidos)) {
            $errores[] = "Seleccione un estado valido.";
        }

        if ($datos["fecha_inicio"] === "") {
            $errores[] = "La fecha de inicio es obligatoria.";
        }

        if ($datos["fecha_fin"] !== "" && $datos["fecha_inicio"] !== "") {
            if ($datos["fecha_fin"] < $datos["fecha_inicio"]) {
                $errores[] = "La fecha de fin no puede ser anterior a la fecha de inicio.";
            }
        }

        return $errores;
    }

    private function valorOpcional($valor)
    {
        return $valor === "" ? null : $valor;
    }
}
