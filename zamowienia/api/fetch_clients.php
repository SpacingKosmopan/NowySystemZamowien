<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require 'db.php';

$result = $conn->query("SELECT id, imie, nazwisko FROM klienci;");

$clients = [];

while($row = $result->fetch_assoc()){
    $clients[] = $row;
}

echo json_encode($clients);
