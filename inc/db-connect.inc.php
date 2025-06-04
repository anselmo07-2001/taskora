<?php

$driver   = 'mysql';
$host     = 'localhost';
$port     = '3307'; 
$dbname   = 'myapp';
$charset  = 'utf8mb4';

$dsn = "$driver:host=$host;port=$port;dbname=$dbname;charset=$charset";

$username = 'your_db_user';
$password = 'your_db_password';

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