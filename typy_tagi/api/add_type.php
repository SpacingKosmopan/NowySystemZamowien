<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require_once "db.php"; 
// zakładam, że tu masz: $conn = new mysqli(...);

$data = json_decode(file_get_contents("php://input"), true);
$nazwa = $data["nazwa"] ?? "";

// walidacja
if (trim($nazwa) === "") {
    http_response_code(400);
    echo json_encode(["error" => "Brak nazwy"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO typy_zamowien (tytul) VALUES (?)");

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Błąd SQL prepare"]);
    exit;
}

$stmt->bind_param("s", $nazwa);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Błąd zapisu"]);
    exit;
}

$stmt->close();

echo json_encode(["success" => true]);