<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require 'db.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : "";

$filter = $conn->real_escape_string($filter);

$sql = "SELECT * FROM zdjecia";

if ($filter !== "") {
    $sql .= " WHERE nazwa_pliku LIKE '%$filter%' 
               OR opis LIKE '%$filter%'";
}

$sql .= " ORDER BY data_dodania DESC";

$result = $conn->query($sql);

$files = [];

while ($row = $result->fetch_assoc()) {
    $files[] = $row;
}

echo json_encode($files);