<?php

$host = "localhost";
$dbname = "myfirst_database2";
$dbusername = "syntax_queen";
$dbpassword = "QUEEN44ZZI";

try {
    $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbusername, $dbpassword);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
