<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$clientId = $data["clientId"];
$typeId = $data["typeId"];
$description = $data["description"];
$date = $data["date"];

//

require 'db.php';

//

$stmt = $conn->prepare(
"INSERT INTO zamowienia 
(klient_id, status, opis, termin_realizacji, typ_id) 
VALUES (?, ?, ?, ?, ?)"
);

$status = "nowe";

$stmt->bind_param(
"isssi",
$clientId,
$status,
$description,
$date,
$typeId
);

$success = $stmt->execute();

echo json_encode([
  "success" => $success
]);