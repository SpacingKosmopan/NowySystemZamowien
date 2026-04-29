<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

//

require '../api/db.php';

//

$sql = "SHOW CREATE TABLE klienci;";
$result = mysqli_query($conn, $sql);
$data = [];
while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}

echo json_encode($data);