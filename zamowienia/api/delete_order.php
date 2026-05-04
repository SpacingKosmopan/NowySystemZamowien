<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"])) {
    http_response_code(400);
    echo json_encode(["status" => false, "error" => "NO_ID"]);
    exit;
}

$id = $data["id"];

$stmt = $conn->prepare("DELETE FROM zamowienia WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => "error 500 - stmt execution",
        "status" => 500
    ]);
}

$stmt->close();
$conn->close();
?>