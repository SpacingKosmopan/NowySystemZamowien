<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$nazwa = $data["nazwa"] ?? "";

if (!$nazwa) {
    http_response_code(400);
    echo json_encode(["error" => "Brak nazwy"]);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO typy_zamowien (tytul) VALUES (?)");
$stmt->execute([$nazwa]);

echo json_encode(["success" => true]);