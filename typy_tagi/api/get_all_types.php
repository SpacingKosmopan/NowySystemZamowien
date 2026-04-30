<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "neworders");
if($conn->connect_error) die("Błąd połączenia");

$sql = "SELECT id, tytul AS nazwa FROM typy_zamowien;";

$res = $conn->query($sql);
$rows = [];
while($r = $res->fetch_assoc()) $rows[] = $r;

echo json_encode($rows);
?>