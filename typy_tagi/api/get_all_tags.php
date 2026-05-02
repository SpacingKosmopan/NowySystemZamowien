<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "neworders");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB_CONNECTION"]);
    exit;
}

$sql = "SELECT id, nazwa FROM tagi;";

$res = $conn->query($sql);

if (!$res) {
    http_response_code(500);
    echo json_encode([
        "error" => "QUERY_FAILED",
        "details" => $conn->error
    ]);
    exit;
}
$rows = [];
while($r = $res->fetch_assoc()) $rows[] = $r;

echo json_encode($rows);
?>