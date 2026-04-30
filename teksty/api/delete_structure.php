<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 0);

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['struktura_id'])) {
    echo json_encode(['success'=>false, 'message'=>'Niepoprawne dane']);
    exit;
}

$struktura_id = $data['struktura_id'];

require 'db.php';

// Funkcja rekurencyjna do usuwania folderu i wszystkich jego dzieci
function deleteStructureRecursively($conn, $id) {
    // 1. Znajdź wszystkie dzieci
    $stmt = $conn->prepare("SELECT id, typ FROM struktury WHERE rodzic_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $children = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    foreach($children as $child) {
        if ($child['typ'] === 'catalogue') {
            // rekurencyjnie usuń podfoldery
            deleteStructureRecursively($conn, $child['id']);
        } else {
            // usuń teksty przypisane do struktury
            $stmtDelText = $conn->prepare("DELETE FROM teksty WHERE struktura_id = ?");
            $stmtDelText->bind_param("i", $child['id']);
            $stmtDelText->execute();
            $stmtDelText->close();

            // usuń strukturę pliku
            $stmtDelStr = $conn->prepare("DELETE FROM struktury WHERE id = ?");
            $stmtDelStr->bind_param("i", $child['id']);
            $stmtDelStr->execute();
            $stmtDelStr->close();
        }
    }

    // usuń sam folder
    $stmtDelFolder = $conn->prepare("DELETE FROM struktury WHERE id = ?");
    $stmtDelFolder->bind_param("i", $id);
    $stmtDelFolder->execute();
    $stmtDelFolder->close();
}

// Usuń wszystkie dzieci i sam folder
deleteStructureRecursively($conn, $struktura_id);

echo json_encode(['success'=>true]);
?>