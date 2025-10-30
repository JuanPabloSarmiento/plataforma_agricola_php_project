<?php
namespace App\Config;

class Database {
    private $host;
    private $usuario;
    private $password;
    private $base_datos;
    private $conexion;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $this->usuario = $_ENV['DB_USER'] ?? 'root';
        $this->password = $_ENV['DB_PASS'] ?? '';
        $this->base_datos = $_ENV['DB_NAME'] ?? 'plataforma_agricola';
        $this->conexion = null;
    }

    public function obtenerConexion() {
        if ($this->conexion) return $this->conexion;
        $mysqli = new \mysqli($this->host, $this->usuario, $this->password, $this->base_datos);
        if ($mysqli->connect_errno) {
            error_log('Error de conexión: ' . $mysqli->connect_error);
            throw new \Exception('No se pudo conectar a la base de datos');
        }
        $mysqli->set_charset('utf8mb4');
        $this->conexion = $mysqli;
        return $this->conexion;
    }
}
