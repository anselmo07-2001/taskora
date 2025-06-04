<?php

$routes = [
    "login" => [
        "GET" => ["controller" => "loginController",  "method" => "showLoginPage"],
        "POST" => ["controller" => "loginController", "method"=> "handleLogin"],
    ],   
];