<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require_once "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? 0;

if (!$id) {
    http_response_code(400);
    echo json_encode(["error" => "Brak ID"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM typy_zamowien WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(["success" => true]);