<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;
header('Content-Type: application/json; charset=utf-8');

// Load .env
$envPath = dirname(__DIR__);
if (file_exists($envPath . '/.env')) {
    $dotenv = Dotenv::createImmutable($envPath);
    $dotenv->load();
}

// Simple routing
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim($_SERVER['BASE'] ?? '', '/');
$path = preg_replace('#^' . preg_quote($base) . '#', '', $uri);

// Normalize for local dev: /plataforma_agricola/public/index.php may be present — accept paths ending with /public/index.php
$path = preg_replace('#.*/public#', '', $path);
$path = $path === '' ? '/' : $path;

require_once __DIR__ . '/../api/helpers/Response.php';
require_once __DIR__ . '/../api/config/Database.php';
require_once __DIR__ . '/../api/middleware/AuthMiddleware.php';
require_once __DIR__ . '/../api/controllers/AuthController.php';
require_once __DIR__ . '/../api/controllers/CursoController.php';

// Very small router
$segments = array_values(array_filter(explode('/', trim($path, '/'))));

if (count($segments) === 0) {
    echo json_encode(['success'=>true, 'message'=>'Plataforma Agrícola - API disponible']);
    exit;
}

if ($segments[0] === 'api') {
    if (($segments[1] ?? '') === 'auth') {
        $auth = new AuthController();
        if ($method === 'POST' && ($segments[2] ?? '') === 'register') {
            echo $auth->registrar();
            exit;
        }
        if ($method === 'POST' && ($segments[2] ?? '') === 'login') {
            echo $auth->login();
            exit;
        }
    }

    if (($segments[1] ?? '') === 'cursos') {
        $controller = new CursoController();
        if ($method === 'GET' && !isset($segments[2])) {
            echo $controller->obtenerTodos();
            exit;
        }
        if ($method === 'GET' && isset($segments[2])) {
            echo $controller->obtenerPorId((int)$segments[2]);
            exit;
        }
    }
}

http_response_code(404);
echo json_encode(['error' => 'Ruta no encontrada']);
