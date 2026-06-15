<?php
/*
 * Cliente.php
 * Modelo encargado de las operaciones CRUD sobre la tabla cliente.
 */
class Cliente
{
    private $conn;
    private $tabla = "cliente";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /* Retorna todos los clientes ordenados por id descendente. */
    public function listar()
    {
        $sql  = "SELECT * FROM " . $this->tabla . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* Retorna un cliente por su id. */
    public function obtenerPorId($id)
    {
        $sql  = "SELECT * FROM " . $this->tabla . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch();
    }

    /* Inserta un nuevo cliente. */
    public function guardar($razon_social, $ruc, $telefono, $correo, $direccion)
    {
        $sql = "INSERT INTO " . $this->tabla . "
                (razon_social, ruc, telefono, correo, direccion)
                VALUES
                (:razon_social, :ruc, :telefono, :correo, :direccion)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":razon_social" => $razon_social,
            ":ruc"          => $ruc,
            ":telefono"     => $telefono,
            ":correo"       => $correo,
            ":direccion"    => $direccion,
        ]);
    }

    /* Actualiza los datos de un cliente existente. */
    public function actualizar($id, $razon_social, $ruc, $telefono, $correo, $direccion)
    {
        $sql = "UPDATE " . $this->tabla . "
                SET razon_social = :razon_social,
                    ruc          = :ruc,
                    telefono     = :telefono,
                    correo       = :correo,
                    direccion    = :direccion
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id"           => $id,
            ":razon_social" => $razon_social,
            ":ruc"          => $ruc,
            ":telefono"     => $telefono,
            ":correo"       => $correo,
            ":direccion"    => $direccion,
        ]);
    }

    /* Elimina un cliente por su id. */
    public function eliminar($id)
    {
        $sql  = "DELETE FROM " . $this->tabla . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }
}
