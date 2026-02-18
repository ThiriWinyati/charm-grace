<?php
// db_connect.php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$DB_HOST = getenv("DB_HOST");
$DB_PORT = getenv("DB_PORT") ?: "4000";
$DB_USER = getenv("DB_USER");
$DB_PASS = getenv("DB_PASS");
$DB_NAME = getenv("DB_NAME") ?: "cosmetics_store";

try {
    if (!$DB_HOST || !$DB_USER || !$DB_PASS) {
        throw new Exception("Missing DB env vars. Check Render Environment variables.");
    }

    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, (int)$DB_PORT);
    $conn->set_charset("utf8mb4");

} catch (Throwable $e) {
    // IMPORTANT: do NOT echo errors (it breaks session_start)
    error_log("DB Connection failed: " . $e->getMessage());
    http_response_code(500);
    die("Database connection error. Check server logs.");
}
