<?php
header("Content-Type: application/json; charset=utf-8");

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require 'db.php';

if (!isset($_FILES["file"])) {
    die("Brak pliku");
}

$file = $_FILES["file"];
if ($file["error"] !== UPLOAD_ERR_OK) {
    die("Błąd uploadu: " . $file["error"]);
}
$opis = $_POST["opis"] ?? null;

// WALIDACJA
$allowed = ["jpg", "jpeg", "png", "pdf"];
$ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    die("Nieprawidłowy typ pliku");
}

if ($file["size"] > 5 * 1024 * 1024) {
    die("Plik za duży");
}

// ZAPIS
$nazwa_pliku = $file["name"];
$unikalna = uniqid() . "." . $ext;
$sciezka = $unikalna;

$dir = "../uploads/";

if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

if (move_uploaded_file($file["tmp_name"], "../uploads/" . $sciezka)) {

    $stmt = $conn->prepare("
        INSERT INTO zdjecia (nazwa_pliku, opis, sciezka) 
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("sss", $nazwa_pliku, $opis, $sciezka);
    $stmt->execute();

    echo "ok";
} else {
    echo "Błąd zapisu";
} 