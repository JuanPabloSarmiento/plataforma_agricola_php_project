<?php
class Curso {
    private $db;
    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function obtenerTodos() {
        $query = "SELECT c.*, cat.nombre as categoria_nombre FROM cursos c LEFT JOIN categorias cat ON c.categoria_id = cat.id WHERE c.activo = 1 ORDER BY c.fecha_creacion DESC";
        $res = $this->db->query($query);
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare('SELECT * FROM cursos WHERE id = ? AND activo = 1 LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $r = $stmt->get_result();
        return $r->fetch_assoc();
    }
}
