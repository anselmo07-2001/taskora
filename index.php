<?php
declare(strict_types=1);

require __DIR__ . "/inc/all.inc.php";

use \App\Support\SessionService;

$container = new \App\Support\Container();

$container->bind("pdo", function() {
    return require __DIR__ . "/inc/db-connect.inc.php";
});
$container->bind("userRepository", function() use($container) {
    $pdo = $container->get("pdo");
    return new \App\Repository\UserRepository($pdo);
});
$container->bind("projectRepository", function() use($container) {
    $pdo = $container->get("pdo");
    return new \App\Repository\ProjectRepository($pdo);
});


$container->bind("AuthController", function() use($container) {
    $userRepository = $container->get("userRepository");
    return new \App\Controllers\AuthController($userRepository);
});
$container->bind("PageController", function() use($container) {
    $userRepository = $container->get("userRepository");
    return new \App\Controllers\PageController($userRepository);
});
$container->bind("AdminController", function() use($container){
    $userRepository = $container->get("userRepository");
    $projectRespository = $container->get("projectRepository");
    return new \App\Controllers\AdminController($userRepository, $projectRespository);
});




SessionService::startSessionIfNotStarted();

$page = isset($_GET["page"]) ? $_GET["page"] : "";
$subPage = $_GET['subPage'] ?? '';
$method = $_SERVER["REQUEST_METHOD"];
  

if ($page === "" && !SessionService::getSessionKey('user')) {
    $page = "login";
}


// Normal route handler
if (isset($routes[$page][$method])) {
    $route = $routes[$page][$method];

    if (!empty($route['auth']) && !SessionService::getSessionKey('user')) {
        $container->get('AuthController')->showLoginPage();
        exit;
    }

    // only allow roles to go in a specific page
    if (!empty($route['roles'])) {
        $role = SessionService::getSessionKey("user")["role"];
        if (!in_array($role, $route['roles'])) {
            http_response_code(403);
            echo "Forbidden";
            exit;
        }
    }

    $controller = $container->get($route['controller']);

    $request = [
        'get' => $_GET,
        "post" => $_POST
    ];

    $controller->{$route['method']}($request);
    exit;
}

http_response_code(404);
echo "Page not found";


