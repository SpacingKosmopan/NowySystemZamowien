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

if (!$data) {
    echo json_encode(["success" => false, "error" => "NO_DATA"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO klienci (imie, nazwisko, email, telefon, adres)
    VALUES (?, ?, ?, ?, ?)
");

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "PREPARE_FAILED"]);
    exit;
}

/*
  UWAGA: mysqli bind_param wymaga typów:
  s = string
*/

$imie = $data['imie'] ?? null;
$nazwisko = $data['nazwisko'] ?? null;
$email = $data['email'] ?? null;
$telefon = $data['telefon'] ?? null;
$adres = $data['adres'] ?? null;

$stmt->bind_param(
    "sssss",
    $imie,
    $nazwisko,
    $email,
    $telefon,
    $adres
);

try {
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode([
            "success" => false,
            "error" => $stmt->error
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();