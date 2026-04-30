<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require 'db.php';

$sql = "SELECT 
    s.id AS struktura_id, 
    s.nazwa AS struktura_nazwa, 
    s.rodzic_id, 
    s.typ, 
    t.id AS tekst_id,
    t.tytul AS tekst_tytul,
    t.tresc AS tekst_tresc,
    t.data_dodania
FROM struktury s
LEFT JOIN teksty t ON s.id = t.struktura_id";

$res = $conn->query($sql);
$rows = [];
while($r = $res->fetch_assoc()) $rows[] = $r;

echo json_encode($rows);
?>