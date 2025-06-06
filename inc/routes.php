<?php

$routes = [
    "login" => [
        "GET" => ["controller" => "loginController",  "method" => "showLoginPage"],
        "POST" => ["controller" => "loginController", "method"=> "handleLogin"],
    ],
    "home" => [
        "GET" => ["controller" => "homeController",  "method" => "showHomePage", "auth" => true, "roles" => ["admin, project_manager, member"]]
    ],
];