<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    require 'db.php';

    $data = json_decode(file_get_contents("php://input"), true);

    $nazwa = $data["nazwa"] ?? null;
    $typ = $data["typ"] ?? "catalogue";
    // Jeśli rodzic_id jest puste, folder jest w katalogu głównym (NULL)
    $rodzic_id = (isset($data["rodzic_id"]) && $data["rodzic_id"] !== "") ? (int)$data["rodzic_id"] : null;

    if (!$nazwa) {
        echo json_encode(["success" => false, "error" => "Brak nazwy"]);
        exit;
    }

    /* INSERT struktury */
    $stmt = $conn->prepare("INSERT INTO struktury (nazwa, rodzic_id, typ) VALUES (?, ?, ?)");
    
    // Bindowanie z obsługą NULL dla rodzic_id
    $stmt->bind_param("sis", $nazwa, $rodzic_id, $typ);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "id" => $conn->insert_id
    ]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
