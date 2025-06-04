<?php

require __DIR__ . "/inc/all.inc.php";

$container = new \App\Support\Container();

$container->bind("pdo", function() {
    return require __DIR__ . "/inc/db-connect.inc.php";
});
$container->bind("loginController", function() {
    return new \App\Controllers\LoginController();
});


$page = isset($_GET["page"]) ? isset($_GET["page"]) : "";
$subPage = $_GET['subPage'] ?? '';
$method = $_SERVER["REQUEST_METHOD"];

if ($page === "") {
    $page = "login";
}


// Normal route handler
if (isset($routes[$page][$method])) {
    $route = $routes[$page][$method];

    // if (!empty($route['auth']) && !SessionService::getSessionKey('user')) {
    //     $container->get('loginController')->showLoginPage();
    //     exit;
    // }

    // if (!empty($route['roles'])) {
    //     $role = getCurrentUserRole();
    //     if (!in_array($role, $route['roles'])) {
    //         http_response_code(403);
    //         echo "Forbidden";
    //         exit;
    //     }
    // }

    $controller = $container->get($route['controller']);
    $controller->{$route['method']}($_GET);
    exit;
}

http_response_code(404);
echo "Page not found";


