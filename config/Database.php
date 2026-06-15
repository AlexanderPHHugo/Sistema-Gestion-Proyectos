<?php
/*
 * Database.php
 * Clase encargada de crear la conexion a MySQL mediante PDO.
 * Esta clase es usada por todos los modelos del sistema.
 */
class Database
{
    private $host     = "";
    private $dbname   = "";
    private $username = "";
    private $password = "";
    private $conn;

    public function conectar()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexion: " . $e->getMessage());
        }

        return $this->conn;
    }
}
