<?php
header("Content-Type: application/json");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

// === connection ===
require 'db.php';

$sql = "SELECT z.*, k.imie, k.nazwisko, t.tytul FROM zamowienia AS Z
    JOIN klienci AS k ON k.id = z.klient_id
    JOIN typy_zamowien AS t ON t.id = z.typ_id
    WHERE termin_realizacji > CURRENT_DATE() 
    ORDER BY termin_realizacji ASC
    LIMIT 3;";

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