<?php
use Firebase\JWT\JWT;
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $modelo;
    private $db;
    private $secret;

    public function __construct() {
        $this->db = (new App\Config\Database())->obtenerConexion();
        $this->modelo = new Usuario($this->db);
        $this->secret = $_ENV['JWT_SECRET'] ?? 'clave_insegura';
    }

    public function registrar() {
        $datos = json_decode(file_get_contents('php://input'), true);
        if (empty($datos['nombre']) || empty($datos['email']) || empty($datos['password'])) {
            http_response_code(400);
            return json_encode(['success'=>false, 'error'=>'Datos incompletos']);
        }
        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            return json_encode(['success'=>false, 'error'=>'Email inválido']);
        }
        $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        try {
            $id = $this->modelo->crear($datos);
            $payload = ['usuario_id'=>$id, 'email'=>$datos['email'], 'exp'=>time() + 86400];
            $token = JWT::encode($payload, $this->secret, 'HS256');
            return json_encode(['success'=>true, 'token'=>$token, 'usuario'=>['id'=>$id, 'email'=>$datos['email'], 'nombre'=>$datos['nombre']]]);
        } catch (Exception $e) {
            http_response_code(500);
            return json_encode(['success'=>false, 'error'=>'Error al registrar usuario']);
        }
    }

    public function login() {
        $datos = json_decode(file_get_contents('php://input'), true);
        if (empty($datos['email']) || empty($datos['password'])) {
            http_response_code(400);
            return json_encode(['success'=>false, 'error'=>'Credenciales incompletas']);
        }
        $usuario = $this->modelo->obtenerPorEmail($datos['email']);
        if (!$usuario) {
            http_response_code(401);
            return json_encode(['success'=>false, 'error'=>'Credenciales inválidas']);
        }
        if (!password_verify($datos['password'], $usuario['password_hash'])) {
            http_response_code(401);
            return json_encode(['success'=>false, 'error'=>'Credenciales inválidas']);
        }
        $payload = ['usuario_id'=>$usuario['id'], 'email'=>$usuario['email'], 'exp'=>time()+86400];
        $token = JWT::encode($payload, $this->secret, 'HS256');
        return json_encode(['success'=>true, 'token'=>$token, 'usuario'=>['id'=>$usuario['id'],'email'=>$usuario['email'],'nombre'=>$usuario['nombre']]]);
    }
}
