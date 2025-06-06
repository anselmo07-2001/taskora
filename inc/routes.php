<?php

$routes = [
    "login" => [
        "GET" => ["controller" => "loginController",  "method" => "showLoginPage"],
        "POST" => ["controller" => "loginController", "method"=> "handleLogin"],
    ],
    "logout" => [
        "POST" => ["controller" => "loginController", "method" => "handleLogout"]
    ],
    "home" => [
        "GET" => ["controller" => "homeController",  "method" => "showHomePage", "auth" => true, "roles" => ["admin, project_manager, member"]]
    ],
];