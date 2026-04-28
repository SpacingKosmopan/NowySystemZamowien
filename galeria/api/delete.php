<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Brak autoryzacji";
    exit;
}

require 'db.php';

$id = $_POST['id'] ?? null;

if (!$id) {
    die("Brak ID");
}

// 1. Pobierz ścieżkę pliku
$stmt = $conn->prepare("SELECT sciezka FROM zdjecia WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$file = $result->fetch_assoc();

if (!$file) {
    die("Nie znaleziono pliku");
}

$path = "../uploads/" . $file['sciezka'];

// 2. Usuń plik z dysku
if (file_exists($path)) {
    unlink($path);
}

// 3. Usuń z bazy
$stmt = $conn->prepare("DELETE FROM zdjecia WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

echo "ok";