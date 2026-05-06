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

$client_id = $data["client_id"];
$opis = $data["opis"];
$kwota = $data["kwota"];
$termin = $data["termin"];
$typ_id = $data["typ_id"];
$tytul = $data["tytul"];

$tagi = json_encode($data["tagi"] ?? []);
if ($tagi === false) {
    die(json_encode([
        "success" => false,
        "json_error" => json_last_error_msg()
    ]));
}

$zalaczniki = json_encode($data["zalaczniki"]);
if ($zalaczniki === false) {
    die(json_encode([
        "success" => false,
        "json_error" => json_last_error_msg()
    ]));
}

$zdjecia = json_encode($data["zdjecia"] ?? []);
if ($zdjecia === false) {
    die(json_encode([
        "success" => false,
        "json_error" => json_last_error_msg()
    ]));
}

$data_utworzenia = date("Y-m-d H:i:s");
$status = "nowe";

$stmt = $conn->prepare(
"INSERT INTO zamowienia 
(klient_id, data_utworzenia, status, kwota, opis, termin_realizacji, tagi, zalaczniki, zdjecia, typ_id, tytul) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param(
"issdsssssis",
$client_id,
$data_utworzenia,
$status,
$kwota,
$opis,
$termin,
$tagi,
$zalaczniki,
$zdjecia,
$typ_id,
$tytul
);

$success = $stmt->execute();

echo json_encode([
  "success" => $success
]);