<?php
class Usuario {
    private $db;
    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function crear($data) {
        $stmt = $this->db->prepare('INSERT INTO usuarios (nombre, email, password_hash, telefono, departamento, municipio, tipo_agricultor) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $telefono = $data['telefono'] ?? null;
        $departamento = $data['departamento'] ?? null;
        $municipio = $data['municipio'] ?? null;
        $tipo = $data['tipo_agricultor'] ?? 'pequeño';
        $stmt->bind_param('sssssss', $data['nombre'], $data['email'], $data['password'], $telefono, $departamento, $municipio, $tipo);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        throw new Exception('No se pudo crear usuario');
    }

    public function obtenerPorEmail($email) {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }
}
