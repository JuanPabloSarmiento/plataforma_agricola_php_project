<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    private $secret;
    public function __construct() {
        $this->secret = $_ENV['JWT_SECRET'] ?? 'clave_insegura';
    }

    public function verificar() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['error'=>'Token no proporcionado']);
            exit;
        }
        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error'=>'Token inválido: ' . $e->getMessage()]);
            exit;
        }
    }
}
