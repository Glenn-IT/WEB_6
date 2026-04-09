<?php

// ----------------------------------------------------------------
// EARLY ERROR DISPLAY (before .env loads)
// Reads APP_DEBUG directly from .env file for early crash visibility
// ----------------------------------------------------------------
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envContents = file_get_contents($envFile);
    if (preg_match('/^APP_DEBUG\s*=\s*(.+)$/m', $envContents, $matches)) {
        $debugValue = strtolower(trim($matches[1]));
        if ($debugValue === 'true') {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            error_reporting(0);
        }
    }
}

// ----------------------------------------------------------------
// SESSION SETUP
// Tries to use a local /sessions folder (good for localhost/XAMPP).
// If the folder is not writable (e.g. on InfinityFree), it falls
// back to the server's default session handler automatically.
// No changes needed here for production.
// ----------------------------------------------------------------
$customSessionPath = __DIR__ . '/sessions';
if (!file_exists($customSessionPath)) {
    @mkdir($customSessionPath, 0755, true);
}

if (is_dir($customSessionPath) && is_writable($customSessionPath)) {
    ini_set('session.save_path', $customSessionPath);
}

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

require __DIR__ . '/vendor/autoload.php'; // Autoload dependencies

use Dotenv\Dotenv;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

// Load environment variables
Dotenv::createImmutable(__DIR__)->load();

// Include necessary files dynamically
$files = [
    '/models/MainModel.php',
    '/middleware/AuthMiddleware.php',
    '/libraries/Helper.php',
    '/view.php',
    '/components/UserController/UserController.php'
];

foreach ($files as $file) {
    require_once __DIR__ . $file;
}



// Initialize database and middleware
$db = new DatabaseClass();
(new AuthMiddleware($db))->handle();

// Define routes
$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($db) {
    $userController = new UserController($db);

    // Predefined routes for UserController
    $routes = [
        ['GET', '/', 'index'],
        ['GET', '/auth', 'index'],
        ['POST', '/auth', 'userLogin'],
        ['POST', '/authRegister', 'authRegister'],
        ['GET', '/forgot_password', 'forgot_password'],
        ['POST', '/forgot_password', 'forgot_password'],
        ['GET', '/otp', 'otp'],
        ['POST', '/otp', 'otp'],
        ['GET', '/changepassword', 'changepassword'],
        ['POST', '/changepassword', 'changepassword'],
        ['POST', '/verify-security-answer', 'verifySecurityAnswer'],
        ['GET', '/userLogout', 'userLogout'],
        ['GET', '/test', 'test'],
        ['GET', '/testEmail', 'testEmail']
    ];

    // Register predefined routes
    foreach ($routes as [$method, $route, $action]) {
        $r->addRoute($method, $route, [$userController, $action]);
    }

    // Dynamic route handling for components
    $r->addRoute(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{component}/{controller}/{method}', function ($component,$controller, $method) use ($db) {
        handleDynamicController($component,$controller, $method, $db);
    });

   
});

// Handle the request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = rawurldecode(strtok($_SERVER['REQUEST_URI'], '?')); // Strip query string

// Remove the base directory from URI if present
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseDir = str_replace('\\', '/', dirname($scriptName));
if ($baseDir !== '/' && strpos($uri, $baseDir) === 0) {
    $uri = substr($uri, strlen($baseDir));
}
if (empty($uri) || $uri === '') {
    $uri = '/';
}

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo '404 Not Found';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo '405 Method Not Allowed';
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
    
        if ($handler instanceof Closure) {
            // Call the closure directly
            call_user_func_array($handler, $vars);
        } else {
            // Assume it's a [Controller, Method] pair
            [$controller, $method] = $handler;
            call_user_func_array([$controller, $method], $vars);
        }
        break;
}

// Function to handle dynamic controllers
function handleDynamicController($component,$controller, $method, $db)
{
    // Format controller name
    $controllerClass = 'ComponentController';
    $controllerPath = __DIR__ . "/modules/Component/ComponentController.php";
    // Input validation
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $controller) || !preg_match('/^[a-zA-Z0-9_]+$/', $method)) {
        http_response_code(400);
        echo 'Invalid controller or method.';
        return;
    }

    // Check if the controller file exists
    if (!file_exists($controllerPath)) {
        http_response_code(404);
        echo "Controller file not found: $controllerPath";
        return;
    }

    // Include and validate the controller class
    require_once $controllerPath;

    if (!class_exists($controllerClass)) {
        http_response_code(404);
        echo "Controller class not found: $controllerClass";
        return;
    }

    $instance = new $controllerClass($db);

    // Validate and call the method
    if (!method_exists($instance, "index")) {
        http_response_code(404);
        echo "Method not found in $controllerClass: $method";
        return;
    }

    $instance->index($component,$controller, $method);
}

