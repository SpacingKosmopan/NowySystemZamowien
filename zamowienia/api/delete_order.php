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
$id = $data["id"];

// zabezpieczenie (BARDZO ważne)
$stmt = $conn->prepare("DELETE FROM zamowienia WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "OK";
} else {
    http_response_code(500);
    echo "Błąd";
}

$stmt->close();
$conn->close();
?>