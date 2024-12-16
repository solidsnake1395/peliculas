<?php
class ConexionBD1 {
    private $conexion;

    public function conectar_bd()
    {
        $this->conexion = new mysqli("localhost", "usuario", "contraseña", "base_de_datos");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    public function cerrar_conexion()
    {
        $this->conexion->close();
    }
}
