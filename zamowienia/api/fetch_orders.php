<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require 'db.php';

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB_ERROR"]);
    exit;
}

$sql = "
SELECT 
  o.id,
  o.klient_id,
  o.typ_id,
  o.opis,
  o.kwota,
  o.termin_realizacji,
  o.status,
  o.data_utworzenia,
  o.tagi,
  o.zalaczniki,
  o.zdjecia,
  o.tytul,
  t.tytul AS typ,
  k.imie,
  k.nazwisko
FROM zamowienia o
JOIN klienci k ON o.klient_id = k.id
JOIN typy_zamowien t ON t.id = o.typ_id
ORDER BY o.data_utworzenia DESC
";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "QUERY_ERROR"]);
    exit;
}

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode($orders);