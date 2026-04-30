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

$order_id = $data["id"];
$client_id = $data["client_id"];
$opis = $data["opis"];
$kwota = $data["kwota"];
$termin = $data["termin"];
$typ_id = $data["typ_id"];
$tagi = json_encode($data["tagi"]);
$zalaczniki = json_encode($data["zalaczniki"]);
$tytul = $data["tytul"];


$zdjecia = json_encode($data["zdjecia"] ?? []);

$stmt = $conn->prepare("
UPDATE zamowienia 
SET klient_id = ?, opis = ?, kwota = ?, termin_realizacji = ?, typ_id = ?, tagi = ?, zalaczniki = ?, zdjecia = ?, tytul = ?
WHERE id = ?
");

$stmt->bind_param(
    "isdssssssi",
    $client_id,
    $opis,
    $kwota,
    $termin,
    $typ_id,
    $tagi,
    $zalaczniki,
    $zdjecia,
    $tytul,
    $order_id
);

$success = $stmt->execute();

echo json_encode(["success" => $success]);