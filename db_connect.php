<?php
$server   = getenv("DB_HOST");
$port     = getenv("DB_PORT") ?: "3306";
$dbname   = getenv("DB_NAME") ?: "cosmetics_store";
$user     = getenv("DB_USER");
$password = getenv("DB_PASS");

try {
    $conn = new PDO(
        "mysql:host=$server;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
