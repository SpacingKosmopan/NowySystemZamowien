<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require "db.php";

if (!isset($_GET['id'])) {
    echo json_encode(["success" => false, "error" => "NO_ID"]);
    exit;
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM klienci WHERE id = ?");

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "PREPARE_FAILED"]);
    exit;
}

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "error" => $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode([
  "success" => true,
  "client" => $row
]);

$stmt->close();
$conn->close();