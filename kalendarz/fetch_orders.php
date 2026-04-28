<?php
header('Content-Type: application/json; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "error" => "NOT_LOGGED"
    ]);
    exit;
}

require_once "db.php";

/*
    DB CHECK
*/
if (!isset($conn) || $conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "error" => "DB_CONNECTION_FAILED"
    ]);
    exit;
}

/*
    SQL — bez duplikatów, czysty SELECT
*/
$sql = "
SELECT 
    o.id AS order_id,
    t.tytul,
    k.imie,
    k.nazwisko,
    o.termin_realizacji,
    o.status
FROM zamowienia o
JOIN klienci k ON o.klient_id = k.id
JOIN typy_zamowien t ON t.id = o.typ_id
ORDER BY o.termin_realizacji ASC
";

$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        "error" => "SQL_ERROR",
        "details" => $conn->error
    ]);
    exit;
}

$events = [];

/*
    Grupowanie po dacie
*/
while ($row = $result->fetch_assoc()) {

    $date = $row['termin_realizacji'];

    if (!isset($events[$date])) {
        $events[$date] = [];
    }

    $events[$date][] = [
        "id" => (int)$row['order_id'],
        "tytul" => $row['tytul'],
        "klient" => $row['imie'] . ' ' . $row['nazwisko'],
        "data" => $date,
        "status" => $row['status']
    ];
}

/*
    Zwracamy JSON zawsze w poprawnym formacie
*/
echo json_encode($events, JSON_UNESCAPED_UNICODE);