<?php
/*
 * Proyecto.php
 * Modelo encargado de las operaciones CRUD sobre la tabla proyecto.
 * Cada proyecto esta asociado a un cliente mediante cliente_id.
 */
class Proyecto
{
    private $conn;
    private $tabla = "proyecto";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /*
     * Lista todos los proyectos haciendo JOIN con cliente
     * para mostrar la razon social del cliente en la vista.
     */
    public function listar()
    {
        $sql = "SELECT p.*, c.razon_social AS cliente_nombre
                FROM " . $this->tabla . " p
                INNER JOIN cliente c ON c.id = p.cliente_id
                ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* Retorna un proyecto por su id incluyendo el nombre del cliente. */
    public function obtenerPorId($id)
    {
        $sql = "SELECT p.*, c.razon_social AS cliente_nombre
                FROM " . $this->tabla . " p
                INNER JOIN cliente c ON c.id = p.cliente_id
                WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch();
    }

    /* Inserta un nuevo proyecto asociado a un cliente. */
    public function guardar($cliente_id, $nombre, $descripcion, $estado, $fecha_inicio, $fecha_fin)
    {
        $sql = "INSERT INTO " . $this->tabla . "
                (cliente_id, nombre, descripcion, estado, fecha_inicio, fecha_fin)
                VALUES
                (:cliente_id, :nombre, :descripcion, :estado, :fecha_inicio, :fecha_fin)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":cliente_id"   => $cliente_id,
            ":nombre"       => $nombre,
            ":descripcion"  => $descripcion,
            ":estado"       => $estado,
            ":fecha_inicio" => $fecha_inicio,
            ":fecha_fin"    => $fecha_fin,
        ]);
    }

    /* Actualiza los datos de un proyecto existente. */
    public function actualizar($id, $cliente_id, $nombre, $descripcion, $estado, $fecha_inicio, $fecha_fin)
    {
        $sql = "UPDATE " . $this->tabla . "
                SET cliente_id   = :cliente_id,
                    nombre       = :nombre,
                    descripcion  = :descripcion,
                    estado       = :estado,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin    = :fecha_fin
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id"           => $id,
            ":cliente_id"   => $cliente_id,
            ":nombre"       => $nombre,
            ":descripcion"  => $descripcion,
            ":estado"       => $estado,
            ":fecha_inicio" => $fecha_inicio,
            ":fecha_fin"    => $fecha_fin,
        ]);
    }

    /* Elimina un proyecto por su id. */
    public function eliminar($id)
    {
        $sql  = "DELETE FROM " . $this->tabla . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    /*
     * Retorna todos los proyectos con datos del cliente
     * para usarlos en la generacion del reporte PDF.
     */
    public function obtenerDatosReporte()
    {
        $sql = "SELECT p.id, p.nombre, p.descripcion, p.estado,
                       p.fecha_inicio, p.fecha_fin,
                       c.razon_social AS cliente_nombre, c.ruc
                FROM " . $this->tabla . " p
                INNER JOIN cliente c ON c.id = p.cliente_id
                ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
