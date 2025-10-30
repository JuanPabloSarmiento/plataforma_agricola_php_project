<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Curso.php';

class CursoController {
    private $modelo;
    private $db;
    public function __construct() {
        $this->db = (new App\Config\Database())->obtenerConexion();
        $this->modelo = new Curso($this->db);
    }

    public function obtenerTodos() {
        try {
            $cursos = $this->modelo->obtenerTodos();
            return json_encode(['success'=>true, 'data'=>$cursos, 'total'=>count($cursos)]);
        } catch (Exception $e) {
            http_response_code(500);
            return json_encode(['success'=>false, 'error'=>'Error interno del servidor']);
        }
    }

    public function obtenerPorId($id) {
        $curso = $this->modelo->obtenerPorId($id);
        if (!$curso) {
            http_response_code(404);
            return json_encode(['success'=>false, 'error'=>'Curso no encontrado']);
        }
        return json_encode(['success'=>true, 'data'=>$curso]);
    }
}
