<?php

define('BASE_URL', getBaseUrl());

function getBaseUrl() {
    $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    $base = rtrim($scriptName, '/\\');
    return "$scheme://$host$base";
}

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function sanitize($dirty)
{
    return filter_var(trim($dirty), FILTER_SANITIZE_SPECIAL_CHARS);
}




