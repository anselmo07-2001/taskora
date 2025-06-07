<?php

$routes = [
    "login" => [
        "GET" => [
            "controller" => "AuthController",
            "method" => "showLoginPage"
        ],
        "POST" => [
            "controller" => "AuthController",
            "method"=> "handleLogin"
        ],
    ],
    "logout" => [
        "POST" => [
            "controller" => "AuthController",
             "method" => "handleLogout"
        ]
    ],
    "home" => [
        "GET" => [
            "controller" => "homeController",
            "method" => "showHomePage",
            "auth" => true,
            "roles" => ["admin", "project_manager", "member"]
        ]
    ],
    "createAccount" => [
        "GET" => [
            "controller" => "AuthController", 
            "method"=> "showCreateAccountPage", 
            "auth" => true, 
            "role" => ["admin"]
        ],
        "POST" => [
            "controller" => "AuthController",
            "method" => "handleCreateAccount",
            "auth" => true, 
            "role" => ["admin"]
        ]
    ],
    "createProject" => [
        "GET" => [
            "controller" => "createProjectController",
            "method" => "showProjectFormPage",
            "auth" => true,
            "role" => ["admin", "project_manager"]
        ],
        "POST" => [
            "controller" => "createProjectController",
            "method" => "handleCreateProject",
            "auth" => true,
            "role" => ["admin", "project_manager"]
        ]
        ],


];