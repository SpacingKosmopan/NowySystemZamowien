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

    $tytul = $data["tytul"] ?? null;
    $tresc = $data["tresc"] ?? "";
    // Zmieniono na struktura_id, bo tak wysyłasz w JS (body: { struktura_id: ... })
    $rodzic_id = (isset($data["struktura_id"]) && $data["struktura_id"] !== "") ? (int)$data["struktura_id"] : null;
    $typ = "text/plain"; 

    if (!$tytul) {
        echo json_encode(["success" => false, "error" => "Brak tytułu"]);
        exit;
    }

    $conn->begin_transaction();

    // 1. Dodajemy rekord do tabeli struktury
    $stmt1 = $conn->prepare("INSERT INTO struktury (nazwa, rodzic_id, typ) VALUES (?, ?, ?)");
    // Teraz przekazujemy dokładnie 3 parametry: string (tytul), integer/null (rodzic), string (typ)
    $stmt1->bind_param("sis", $tytul, $rodzic_id, $typ);
    $stmt1->execute();
    
    $nowe_struktura_id = $conn->insert_id;

    // 2. Dodajemy rekord do tabeli teksty powiązany z nową strukturą
    $stmt2 = $conn->prepare("INSERT INTO teksty (tytul, tresc, struktura_id) VALUES (?, ?, ?)");
    $stmt2->bind_param("ssi", $tytul, $tresc, $nowe_struktura_id);
    $stmt2->execute();
    
    $nowy_tekst_id = $conn->insert_id;

    $conn->commit();

    echo json_encode([
        "success" => true, 
        "tekst_id" => $nowy_tekst_id,
        "id" => $nowy_tekst_id 
    ]);

} catch (Exception $e) {
    if (isset($conn)) $conn->rollback();
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
