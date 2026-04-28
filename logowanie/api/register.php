<?php
require 'db.php';

$name = $_POST['name'];
$password = $_POST['password'];

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO uzytkownicy (nazwa, password_hash) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $hash);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "ERROR";
}