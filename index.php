<?php
declare(strict_types=1);

require __DIR__ . "/inc/all.inc.php";

$container = new \App\Support\Container();

$container->bind("pdo", function() {
    return require __DIR__ . "/inc/db-connect.inc.php";
});
$container->bind("userRepository", function() use($container) {
    $pdo = $container->get("pdo");
    return new \App\Repository\UserRespository($pdo);
});


$container->bind("loginController", function() use($container) {
    $userRespository = $container->get("userRepository");
    return new \App\Controllers\LoginController($userRespository);
});





$page = isset($_GET["page"]) ? $_GET["page"] : "";
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

    $request = [
        'get' => $_GET,
        "post" => $_POST
    ];

    $controller->{$route['method']}($request);
    exit;
}

http_response_code(404);
echo "Page not found";


