<?php

$servername = 'localhost';
$database = 'video_chat_db';
$database1 = "database";
$username = 'root';
$password = 'root1234';
$charset = 'utf8mb4';
$dbPrefix = 'lsv_';


$dsn = "mysql:host=$servername;dbname=$database;charset=$charset";
$dsn1 = "mysql:host=$servername;dbname=$database1;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $username, $password, $options);
     $pdo1 = new PDO($dsn1, $username, $password, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}