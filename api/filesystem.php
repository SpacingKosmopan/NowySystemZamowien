<?php
header("Content-Type: application/json; charset=utf-8");

$saveDir = __DIR__ . "/files/";

if (!is_dir($saveDir)) {
    mkdir($saveDir, 0777, true);
}

function getPath($filename) {
    global $saveDir;

    $filename = basename($filename);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    $allowed = ["txt", "json"];

    if (!in_array($ext, $allowed)) {
        return false;
    }

    return $saveDir . $filename;
}

function ensureFile($path, $defaultContent) {
    if (!file_exists($path)) {
        file_put_contents($path, $defaultContent);
    }
}

function loadFile($filename, $defaultContent = "{}") {
    $path = getPath($filename);
    if (!$path) {
        return [
            "success" => false,
            "message" => "Invalid filename"
        ];
    }
    ensureFile($path, $defaultContent);

    $content = file_get_contents($path);

    if ($content === false) {
        return [
            "success" => false,
            "message" => "Cannot read file"
        ];
    }

    return [
        "success" => true,
        "content" => $content
    ];
}

function saveFile($filename, $content) {
    $path = getPath($filename);
    if (!$path) {
        return [
            "success" => false,
            "message" => "Invalid filename"
        ];
    }

    $result = file_put_contents($path, $content);
    if ($result === false) {
        return [
            "success" => false,
            "message" => "Cannot write file"
        ];
    }

    return [
        "success" => true
    ];
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON"
    ]);
    exit;
}

$action = $data["action"] ?? "";
$filename = $data["filename"] ?? "";
$content = $data["content"] ?? "";

if ($action === "load") {
    echo json_encode(
        loadFile($filename, $content)
    );
    exit;
}

if ($action === "save") {
    echo json_encode(
        saveFile($filename, $content)
    );
    exit;
}

echo json_encode([
    "success" => false,
    "message" => "Unknown action"
]);