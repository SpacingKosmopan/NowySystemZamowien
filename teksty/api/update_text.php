<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"];
$tresc = $data["tresc"];

require 'db.php';

$stmt = $conn->prepare("
UPDATE teksty 
SET tresc = ?
WHERE id = ?
");

$stmt->bind_param("si", $tresc, $id);

$success = $stmt->execute();

echo json_encode(["success" => $success]);