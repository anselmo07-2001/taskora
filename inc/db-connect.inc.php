<?php

$driver   = 'mysql';
$host     = 'localhost';
$port     = '3307'; 
$dbname   = 'db_taskora';
$charset  = 'utf8mb4';

$dsn = "$driver:host=$host;port=$port;dbname=$dbname;charset=$charset";

$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
}
catch (PDOException $e) {
    // var_dump($e->getMessage());
    echo 'A problem occured with the database connection...';
    die();
}

return $pdo;