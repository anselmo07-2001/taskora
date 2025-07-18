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
$container->bind("taskRepository", function() use($container) {
    $pdo = $container->get("pdo");
    return new \App\Repository\TaskRepository($pdo);
});
$container->bind("taskNotesRepository", function() use($container) {
    $pdo = $container->get("pdo");
    return new \App\Repository\TaskNotesRepository($pdo);
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
    $taskRepository = $container->get("taskRepository");
    $projectNotesRepository = $container->get("projectNotesRepository");
    $projectNotesController = $container->get("ProjectNotesController");
    $taskController = $container->get("TaskController"); // please check this later if this code is really needed
    $projectPanelService = $container->get("ProjectPanelService");
    $taskNotesRepository = $container->get("taskNotesRepository");


    return new \App\Controllers\PageController($userRepository, $projectRepository, $projectNotesRepository, 
                $projectNotesController, $taskController, $projectPanelService, $taskRepository, $taskNotesRepository);
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
$container->bind("ProjectPanelService", function () use ($container) {
    $projectRepository = $container->get("projectRepository");
    $projectNotesRepository = $container->get("ProjectNotesController");
    $taskRepository = $container->get("taskRepository");
    return new \App\Support\ProjectPanelService($projectRepository, $projectNotesRepository, $taskRepository);
});
$container->bind("TaskController", function() use($container){
    $projectPanelService = $container->get("ProjectPanelService");
    $taskRepository = $container->get("taskRepository");
    $taskNotesRepository = $container->get("taskNotesRepository");
    return new \App\Controllers\TaskController($projectPanelService, $taskRepository, $taskNotesRepository);
});
$container->bind("TaskNotesController", function() use($container) {
    $taskNotesRepository = $container->get("taskNotesRepository");
    $taskRepository = $container->get("taskRepository");
    return new \App\Controllers\TaskNotesController($taskNotesRepository, $taskRepository);
});
$container->bind("AccountController", function() use($container) {
    $userRepository = $container->get("userRepository");
    return new \App\Controllers\AccountController($userRepository);
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
            $container->get("PageController")->showError404Page();
            //echo "<h1>Forbidden Route Please contact your Administrator to access this page</h1>";
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


