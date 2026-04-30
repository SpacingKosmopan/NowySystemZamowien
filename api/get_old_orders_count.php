<?php
// !!! co będzie zwracać !!!
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

// === connection ===
require 'db.php';

$sql = "SELECT COUNT(*) AS amount FROM zamowienia 
    WHERE termin_realizacji < DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY);";

$result = $conn->query($sql);

if (!$result) {
    //die("SQL error: " . $conn->error);
    http_response_code(500);
    echo json_encode([
        "message" => "SQL error",
        "error" => $conn->error
    ]);
    return;
}

$data = [];
while ($row = $result->fetch_assoc()){
    // dodaj wiersz to arraya
    $data[] = $row;
}

echo json_encode($data);