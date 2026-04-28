<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

/**
 * Funkcja służy do wypisania wszystkich zależności i kluczy danej tabeli
 */
function DebugTable(){
require 'db.php';

$sql = "SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE 
    TABLE_SCHEMA = 'neworders'
    AND REFERENCED_TABLE_NAME IS NOT NULL";

$result = $conn->query($sql);

if (!$result) {
    die("SQL error: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    print_r($row);
    echo "<br><br>";
}
}

DebugTable();