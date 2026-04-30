<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

// === connection ===
require 'db.php';

$sql = "SELECT imie, nazwisko, COUNT(*) AS ilosc_zamowien 
    FROM klienci AS k 
    JOIN zamowienia AS o 
    WHERE k.id = o.klient_id 
    GROUP BY k.id 
    ORDER BY ilosc_zamowien DESC
    LIMIT 5;";

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
    $data[] = $row;
}

echo json_encode($data);