<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "error" => "NO_DATA"]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE klienci
    SET imie = ?, nazwisko = ?, email = ?, telefon = ?, adres = ?
    WHERE id = ?
");

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "PREPARE_FAILED"]);
    exit;
}

/*
  WAŻNE:
  s = string
  i = integer (id)
*/
$imie = $data['imie'] ?? null;
$nazwisko = $data['nazwisko'] ?? null;
$email = $data['email'] ?? null;
$telefon = $data['telefon'] ?? null;
$adres = $data['adres'] ?? null;
$id = (int)($data['id'] ?? 0);

$stmt->bind_param(
    "sssssi",
    $imie,
    $nazwisko,
    $email,
    $telefon,
    $adres,
    $id
);

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