<?php

require __DIR__ . "/inc/all.inc.php";

$container = new \App\Support\Container();

$container->bind("pdo", function() {
    return require __DIR__ . "/inc/db-connect.inc.php";
});

