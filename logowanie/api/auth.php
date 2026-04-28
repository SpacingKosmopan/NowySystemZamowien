<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$stmt = $conn->prepare("SELECT status, nazwa FROM uzytkownicy WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if (!$user || $user['status'] !== 'aktywny') {
    http_response_code(403);
    echo "NOT_ACTIVE";
    exit;
}

http_response_code(200);
echo $user['nazwa'];
