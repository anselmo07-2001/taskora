<?php

$routes = [
    "login" => [
        "GET" => [
            "controller" => "PageController",
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
            "controller" => "PageController",
            "method" => "showHomePage",
            "auth" => true,
            "roles" => ["admin", "project_manager", "member"]
        ]
    ],
    "createAccount" => [
        "GET" => [
            "controller" => "PageController", 
            "method"=> "showCreateAccountPage", 
            "auth" => true, 
            "role" => ["admin"]
        ],
        "POST" => [
            "controller" => "AdminController",
            "method" => "handleCreateAccount",
            "auth" => true, 
            "role" => ["admin"]
        ]
    ],
    "createProject" => [
        "GET" => [
            "controller" => "PageController",
            "method" => "showProjectFormPage",
            "auth" => true,
            "role" => ["admin", "project_manager"]
        ],
        "POST" => [
            "controller" => "ProjectController",
            "method" => "createProject",
            "auth" => true,
            "role" => ["admin", "project_manager"]
        ]
    ],
    "projects" => [
        "GET" => [
            "controller" => "PageController",
            "method" => "showProjects",
            "auth" => true,
            "role" => ["admin", "project_manager", "member"]
        ]
    ],
    
    // "projectPanel" => [
    //     "GET" => [
    //         "default" => [
    //             "controller" => "PageController",
    //             "method" => "showProject",
    //             "auth" => true,
    //             "role" => ["admin", "project_manager", "member"]
    //         ]
    //     ]
    // ]

    "projectPanel" => [
        "GET" => [       
                "controller" => "PageController",
                "method" => "showProject",
                "auth" => true,
                "role" => ["admin", "project_manager", "member"]
        ],
        "POST" => [       
                "controller" => "ProjectNotesController",
                "method" => "createProjectNote",
                "auth" => true,
                "role" => ["admin", "project_manager", "member"]
        ]
    ]


];