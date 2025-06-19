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
            "roles" => ["admin"]
        ],
        "POST" => [
            "controller" => "AdminController",
            "method" => "handleCreateAccount",
            "auth" => true, 
            "roles" => ["admin"]
        ]
    ],
    "createProject" => [
        "GET" => [
            "controller" => "PageController",
            "method" => "showProjectFormPage",
            "auth" => true,
            "roles" => ["admin", "project_manager"]
        ],
        "POST" => [
            "controller" => "ProjectController",
            "method" => "createProject",
            "auth" => true,
            "roles" => ["admin", "project_manager"]
        ]
    ],
    "projects" => [
        "GET" => [
            "controller" => "PageController",
            "method" => "showProjects",
            "auth" => true,
            "roles" => ["admin", "project_manager", "member"]
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
                "roles" => ["admin", "project_manager", "member"]
        ],
        "POST" => [       
                "controller" => "ProjectNotesController",
                "method" => "createProjectNote",
                "auth" => true,
                "roles" => ["admin", "project_manager", "member"]
        ]
    ],

    "updateProjectStatus" => [
        "POST" => [
            "controller" => "ProjectController",
            "method" => "updateProjectStatus",
            "auth" => true,
            "roles" => ["admin", "project_manager"]
        ]
    ],
    "updateProjectNote" => [
        "POST" => [
            "controller" => "ProjectNotesController",
            "method" => "updateProjectNote",
            "auth" => true,
            "roles" => ["admin", "project_manager"]
        ]
    ],
    "deleteProjectNote" => [
        "POST" => [
            "controller" => "ProjectNotesController",
            "method" => "deleteProjectNote",
            "auth" => true,
            "roles" => ["admin", "project_manager"]
        ]
    ],


    "createTask" => [
        "POST" => [
            "controller" => "TaskController",
            "method" => "createTask",
            "auth" => true,
            "roles" => ["admin", "project_manager"]
        ]
    ],

    "taskPanel" => [
        "GET" => [       
                "controller" => "PageController",
                "method" => "showTask",
                "auth" => true,
                "roles" => ["admin", "project_manager", "member"]
        ],
    ],
    "createTaskNote" => [
        "POST" => [       
                "controller" => "TaskNotesController",
                "method" => "createTaskNote",
                "auth" => true,
                "roles" => ["admin", "project_manager", "member"]
        ],
    ],
    "editTaskNote" => [
        "POST" => [
                "controller" => "TaskNotesController",
                "method" => "editTaskNote",
                "auth" => true,
                "roles" => ["admin", "project_manager", "member"]
        ]
    ]


];