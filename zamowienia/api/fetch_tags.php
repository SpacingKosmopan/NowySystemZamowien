<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require 'db.php';

$result = $conn->query("SELECT id, nazwa FROM tagi");

$tags = [];

while ($row = $result->fetch_assoc()) {
    $tags[] = [
        "id" => $row["id"],
        "name" => $row["nazwa"]
    ];
}

echo json_encode($tags);

?>