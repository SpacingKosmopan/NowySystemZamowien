<?php
header("Content-Type: application/json");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

// === connection ===
require 'db.php';

$sql = "SELECT 
            CONCAT(
                LPAD(EXTRACT(MONTH FROM termin_realizacji), 2, '0'),
                '-',
                EXTRACT(YEAR FROM termin_realizacji)
            ) AS miesiac,
            COUNT(*) AS ilosc
        FROM zamowienia
        WHERE status = 'zrealizowane'
        GROUP BY 
            EXTRACT(YEAR FROM termin_realizacji),
            EXTRACT(MONTH FROM termin_realizacji)
        ORDER BY 
            EXTRACT(YEAR FROM termin_realizacji),
            EXTRACT(MONTH FROM termin_realizacji);";

$result = $conn->query($sql);

if (!$result) {
    //die("SQL error: " . $conn->error);
    http_response_code(500);
    echo json_encode([
        "message" => "SQL error",
        "error" => $conn->error
    ]);
    return;
}

$data = [];
while ($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);