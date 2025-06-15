<?php
declare(strict_types=1);
date_default_timezone_set("Asia/Manila");

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
$container->bind("projectNotesRepository", function() use($container) {
    $pdo = $container->get("pdo");
    return new \App\Repository\ProjectNotesRepository($pdo);
});


$container->bind("AuthController", function() use($container) {
    $userRepository = $container->get("userRepository");
    return new \App\Controllers\AuthController($userRepository);
});
$container->bind("ProjectNotesController", function() use($container) {
    $projectRepository = $container->get("projectRepository");
    $projectNotesRepository = $container->get("projectNotesRepository");
    return new \App\Controllers\ProjectNotesController($projectRepository, $projectNotesRepository);
});
$container->bind("PageController", function() use($container) {
    $userRepository = $container->get("userRepository");
    $projectRepository = $container->get("projectRepository");
    $projectNotesRepository = $container->get("projectNotesRepository");
    $projectNotesController = $container->get("ProjectNotesController");
    $taskController = $container->get("TaskController");
    return new \App\Controllers\PageController($userRepository, $projectRepository, $projectNotesRepository, $projectNotesController, $taskController);
});
$container->bind("AdminController", function() use($container){
    $userRepository = $container->get("userRepository");
    $projectRepository = $container->get("projectRepository");
    return new \App\Controllers\AdminController($userRepository, $projectRepository);
});
$container->bind("ProjectController", function() use($container) {
    $userRepository = $container->get("userRepository");
    $projectRepository = $container->get("projectRepository");
    $projectNotesRepository = $container->get("projectNotesRepository");
    return new \App\Controllers\ProjectController($userRepository, $projectRepository, $projectNotesRepository);
});
$container->bind("TaskController", function(){
    return new \App\Controllers\TaskController();
});



SessionService::startSessionIfNotStarted();

$page = isset($_GET["page"]) ? $_GET["page"] : "";
$subPage = $_GET['subPage'] ?? '';
$method = $_SERVER["REQUEST_METHOD"];
  

if ($page === "" && !SessionService::getSessionKey('user')) {
    $page = "login";
}


// if ($page === "projectPanel" && $method === "GET") {
//     $panelRoutes = $routes["projectPanel"]["GET"];

//     // Find subpage route or default
//     if ($subPage) {     
//         if (isset($panelRoutes[$subPage])) {
//             $subRoute = $panelRoutes[$subPage];
//         } else {
//             $subRoute = null;
//         }
//     } else {
//         if (isset($panelRoutes["default"])) {
//             $subRoute = $panelRoutes["default"];
//         } else {
//             $subRoute = null;
//         }
//     }

//     if (!$subRoute) {
//         http_response_code(404);
//         echo "Subpage not found";
//         exit;
//     }

//     // Auth check
//     if (!empty($subRoute['auth']) && !SessionService::getSessionKey('user')) {
//         $container->get('loginController')->showLoginPage();
//         exit;
//     }

//     // Role check
//     if (!empty($route['roles'])) {
//         $role = SessionService::getSessionKey("user")["role"];
//         if (!in_array($role, $route['roles'])) {
//             http_response_code(403);
//             echo "Forbidden";
//             exit;
//         }
//     }

//     $controller = $container->get($subRoute['controller']);
//     $controller->{$subRoute['method']}($_GET);
//     exit;
// }



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
            echo "<h1>Forbidden Route Please contact your Administrator to access this page</h1>";
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


