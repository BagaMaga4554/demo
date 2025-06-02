<?php
$host = 'localhost';
$db = 'cleaning_service';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host; dbname=$db";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) { 
    die("Ошибка подключения: ". $e->getMessage());;
}
?>