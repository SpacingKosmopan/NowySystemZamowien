<?php
header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$order_id = $data["id"] ?? null;
$status = $data["status"] ?? null;

if (!$order_id || !$status) {
    echo json_encode(["success" => false, "error" => "Brak danych"]);
    exit;
}

$allowed_statuses = ["anulowane", "nowe", "w realizacji", "zrealizowane"];
if (!in_array($status, $allowed_statuses)) {
    echo json_encode(["success" => false, "error" => "Nieprawidłowy status"]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "neworders");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "DB error"]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE zamowienia 
    SET status = ? 
    WHERE id = ?
");

$stmt->bind_param("si", $status, $order_id);

$success = $stmt->execute();

echo json_encode(["success" => $success]);

$stmt->close();
$conn->close();