<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'])) {
    echo json_encode(["success" => false, "error" => "NO_ID"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM klienci WHERE id = ?");

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "PREPARE_FAILED"]);
    exit;
}

/*
  id najczęściej jest int → używamy "i"
*/
$id = (int)$data['id'];

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode([
        "success" => false,
        "error" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();