<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "error" => "NOT_LOGGED"]);
    exit;
}

require "db.php";

$stmt = $conn->prepare("
    SELECT zamowienia.*, typy_zamowien.tytul AS typ
    FROM zamowienia 
    JOIN typy_zamowien ON zamowienia.typ_id = typy_zamowien.id 
    WHERE klient_id = ?
    ORDER BY termin_realizacji DESC;
");

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "PREPARE_FAILED"]);
    exit;
}

$clientId = $_GET['id'] ?? null;
$clientId = (int)$clientId;

if (!isset($clientId) || !is_numeric($clientId)) {
    echo json_encode(["success" => false, "error" => "INVALID_ID: {$clientId}"]);
    exit;
}

$stmt->bind_param(
    "i",
    $clientId
);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode([
        "success" => true,
        "orders" => $orders
    ]);
} else {
    echo json_encode([
        "success" => false,
        "error" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();