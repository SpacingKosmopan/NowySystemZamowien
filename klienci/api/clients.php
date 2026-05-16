<?php
// === REFACTOR TEST ===
// >> single endpoint <<

// == 1 == Ustawienia, weryfikacja, inicjacja
session_start();

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "NOT_LOGGED"]);
    exit;
}

require "db.php";

// == 2 == Pobranie metody i uruchomienie odpowiedzialnej funkcji
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // GET api/clients.php?id=#
        if (isset($_GET['id'])) {
            getClient($conn, $_GET['id']);
        } else {
            getClients($conn);
        }
        break;

    case 'POST':
        createClient($conn);
        break;

    case 'PATCH':
        updateClient($conn);
        break;

    case 'DELETE':
        deleteClient($conn);
        break;
}

function getClient($conn, $clientId){
    $id = (int)$clientId;
    $stmt = $conn->prepare(
        "SELECT * 
        FROM klienci 
        WHERE id = ?
    ");

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "success" => false,
            "error" => "STATEMENT_PREPARE_FAILED"
        ]);
        exit;
    }

    $stmt->bind_param("i", $id);

    if (!$stmt->execute()) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "success" => false, 
            "error" => $stmt->error
        ]);
        exit;
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        http_response_code(404);
        echo json_encode([         
            "success" => false,
            "error" => "CLIENT_NOT_FOUND"
        ]);
        exit;
    }

    http_response_code(200); // OK
    echo json_encode([
        "success" => true,
        "client" => $row
    ]);

    $stmt->close();
}

function getClients($conn){
    $sql = "SELECT * 
        FROM klienci 
        ORDER BY nazwisko ASC";

    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "success" => false,
            "error" => $conn->error
        ]);
        exit;
    }

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    http_response_code(200); // OK
    echo json_encode([
        "success" => true,
        "clients" => $data
    ]);
}

function createClient($conn){
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        http_response_code(400); // Bad Request
        echo json_encode([
            "success" => false, 
            "error" => "NO_DATA"
        ]);
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO klienci (imie, nazwisko, email, telefon, adres)
        VALUES (?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "success" => false, 
            "error" => "PREPARE_FAILED"
        ]);
        exit;
    }

    $imie = $data['imie'] ?? null;
    $nazwisko = $data['nazwisko'] ?? null;
    $email = $data['email'] ?? null;
    $telefon = $data['telefon'] ?? null;
    $adres = $data['adres'] ?? null;

    $stmt->bind_param(
        "sssss",
        $imie,
        $nazwisko,
        $email,
        $telefon,
        $adres
    );

    try {
        if ($stmt->execute()) {
            http_response_code(201); // Created
            echo json_encode([
                "success" => true
            ]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode([
                "success" => false,
                "error" => $stmt->error
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);
    }

    $stmt->close();
}

function updateClient($conn){
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        http_response_code(400); // Bad Request
        echo json_encode([
            "success" => false, 
            "error" => "NO_DATA"
        ]);
        exit;
    }

    $stmt = $conn->prepare("
        UPDATE klienci
        SET imie = ?, nazwisko = ?, email = ?, telefon = ?, adres = ?
        WHERE id = ?
    ");

    if (!$stmt) {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "success" => false, 
            "error" => "PREPARE_FAILED"
        ]);
        exit;
    }

    $imie = $data['imie'] ?? null;
    $nazwisko = $data['nazwisko'] ?? null;
    $email = $data['email'] ?? null;
    $telefon = $data['telefon'] ?? null;
    $adres = $data['adres'] ?? null;
    $id = (int)($data['id'] ?? 0);

    $stmt->bind_param(
        "sssssi",
        $imie,
        $nazwisko,
        $email,
        $telefon,
        $adres,
        $id
    );

    if ($stmt->execute()) {
        http_response_code(200); // OK
        echo json_encode([
            "success" => true
        ]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "success" => false,
            "error" => $stmt->error
        ]);
    }

    $stmt->close();
}

function deleteClient($conn){
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id'])) {
        http_response_code(400); // Bad Request
        echo json_encode([
            "success" => false, 
            "error" => "NO_ID"
        ]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM klienci WHERE id = ?");

    if (!$stmt) {
        echo json_encode([
            "success" => false, 
            "error" => "PREPARE_FAILED",
            "internal_code" => "err-01"
        ]);
        exit;
    }

    $id = (int)$data['id'];

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        http_response_code(200); // OK
        echo json_encode([
            "success" => true
        ]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode([
            "success" => false,
            "error" => $stmt->error
        ]);
    }

    $stmt->close();
}

$conn->close();