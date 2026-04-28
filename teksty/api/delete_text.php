<?php
header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 0);

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['tekst_id']) || !isset($data['struktura_id'])) {
    echo json_encode(['success'=>false, 'message'=>'Niepoprawne dane']);
    exit;
}

$tekst_id = $data['tekst_id'];
$struktura_id = $data['struktura_id'];

require 'db.php';

$stmt = $conn->prepare("DELETE FROM teksty WHERE id = ?");
$stmt->bind_param("i", $tekst_id);
$success1 = $stmt->execute();

$stmt2 = $conn->prepare("DELETE FROM struktury WHERE id = ?");
$stmt2->bind_param("i", $struktura_id);
$success2 = $stmt2->execute();

$success = $success1 && $success2;

echo json_encode(['success'=>$success]);
?>