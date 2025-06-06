<?php

$routes = [
    "login" => [
        "GET" => ["controller" => "AuthController",  "method" => "showLoginPage"],
        "POST" => ["controller" => "AuthController", "method"=> "handleLogin"],
    ],
    "logout" => [
        "POST" => ["controller" => "AuthController", "method" => "handleLogout"]
    ],
    "home" => [
        "GET" => ["controller" => "homeController",  "method" => "showHomePage", "auth" => true, "roles" => ["admin, project_manager, member"]]
    ],
];