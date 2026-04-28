<?php
session_start();
require 'db.php';

$name = $_POST['name'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password_hash, status FROM uzytkownicy WHERE nazwa = ?");
$stmt->bind_param("s", $name);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo "INVALID";
    exit;
}

$status = $user['status'] ?? 'oczekujacy';

if ($status === 'zablokowany') {
    http_response_code(403);
    echo "Użytkownik jest zablokowany";
    exit;
}

if ($status !== 'aktywny') {
    http_response_code(403);
    echo "Użytkownik oczekuje na weryfikację";
    exit;
}

// OK
session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];

echo "OK";