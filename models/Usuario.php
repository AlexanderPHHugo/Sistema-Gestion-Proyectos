<?php
/*
 * Usuario.php
 * Modelo encargado de las operaciones sobre la tabla usuario.
 */
class Usuario
{
    private $conn;
    private $tabla = "usuario";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /*
     * Busca un usuario por su nombre de usuario.
     * Se usa en el proceso de autenticacion del login.
     */

        public function listar()
    {
        $sql = "SELECT id, nombre, ape_pat, ape_mat, dni, usuario, rol 
                FROM " . $this->tabla . " 
                ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

        /* Retorna un usuario por su id. */
    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM " . $this->tabla . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch();
    }

    /* Actualiza los datos de un usuario existente. */
    public function actualizar($id, $nombre, $ape_pat, $ape_mat, $dni, $usuario, $rol)
    {
        $sql = "UPDATE " . $this->tabla . "
                SET nombre = :nombre,
                    ape_pat = :ape_pat,
                    ape_mat = :ape_mat,
                    dni = :dni,
                    usuario = :usuario,
                    rol = :rol
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id"       => $id,
            ":nombre"   => $nombre,
            ":ape_pat"  => $ape_pat,
            ":ape_mat"  => $ape_mat,
            ":dni"      => $dni,
            ":usuario"  => $usuario,
            ":rol"      => $rol,
        ]);
    }

    /* Elimina un usuario por su id (excepto el propio admin). */
    public function eliminar($id)
    {
        $sql = "DELETE FROM " . $this->tabla . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":id" => $id]);
    }

    /* Cambia la contraseña de un usuario. */
    public function cambiarPassword($id, $passwordHash)
    {
        $sql = "UPDATE " . $this->tabla . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":password" => $passwordHash, ":id" => $id]);
    }

    public function obtenerPorUsuario($usuario)
    {
        $sql  = "SELECT * FROM " . $this->tabla . " WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":usuario" => $usuario]);
        return $stmt->fetch();
    }

    /*
     * Registra un nuevo usuario en la base de datos.
     * La contrasena debe llegar ya encriptada desde el controlador.
     */
    public function guardar($datos)
    {
        $sql = "INSERT INTO " . $this->tabla . "
                (nombre, ape_pat, ape_mat, dni, usuario, password, rol)
                VALUES
                (:nombre, :ape_pat, :ape_mat, :dni, :usuario, :password, :rol)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":nombre"   => $datos["nombre"],
            ":ape_pat"  => $datos["ape_pat"],
            ":ape_mat"  => $datos["ape_mat"],
            ":dni"      => $datos["dni"],
            ":usuario"  => $datos["usuario"],
            ":password" => $datos["password"],
            ":rol"      => $datos["rol"],
        ]);
    }
}
