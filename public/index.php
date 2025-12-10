<?php
// FRONT CONTROLLER

// Cargar rutas principales
require_once __DIR__ . '/../app/config/config.php';

// Autoload manual
spl_autoload_register(function ($class) {

    // Core
    $corePath = APP_PATH . "core/$class.php";
    if (file_exists($corePath)) {
        require_once $corePath;
        return;
    }

    // Controllers
    $controllerPath = APP_PATH . "controllers/$class.php";
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        return;
    }

    // Models
    $modelPath = APP_PATH . "models/$class.php";
    if (file_exists($modelPath)) {
        require_once $modelPath;
        return;
    }
    // Helpers
    $helperPath = APP_PATH . "helpers/$class.php";
    if (file_exists(APP_PATH . "/helpers/" . $class . ".php")) {
        require_once APP_PATH . "/helpers/" . $class . ".php";
        return;
    }
});

// Procesar URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

// Controlador
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . "Controller" : "HomeController";

// Método
$method = $urlParts[1] ?? "index";

// Parámetro (si existe)
$param = $urlParts[2] ?? null;

// Validar controlador
if (!file_exists(APP_PATH . "controllers/$controllerName.php")) {
    require_once APP_PATH . "controllers/ErrorHandler.php";
    ErrorHandler::mostrar404();
    exit;
}

$controller = new $controllerName();

// Validar método
if (!method_exists($controller, $method)) {
    require_once APP_PATH . "controllers/ErrorHandler.php";
    ErrorHandler::mostrar404();
    exit;
}

// Ejecutar acción
($param !== null)
    ? $controller->$method($param)
    : $controller->$method();
    