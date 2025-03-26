<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: /assets/php/login.php");
    exit();
}

ob_start();
header("Content-Type: application/json");

$captionsFile = $_SERVER['DOCUMENT_ROOT'] . "/assets/data/captions.json";
$captions = file_exists($captionsFile) ? json_decode(file_get_contents($captionsFile), true) : [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $gallery = $_POST["gallery"] ?? '';
    $file = $_POST["file"] ?? '';
    $filePath = $_SERVER['DOCUMENT_ROOT'] . "/gallery images/" . $gallery . "/" . $file;

    if (file_exists($filePath) && unlink($filePath)) {
        if (isset($captions[$gallery][$file])) {
            unset($captions[$gallery][$file]);
            if (empty($captions[$gallery])) unset($captions[$gallery]);
            file_put_contents($captionsFile, json_encode($captions, JSON_PRETTY_PRINT));
        }
        echo json_encode(["success" => true, "message" => "Zdjęcie zostało usunięte."]);
    } else {
        echo json_encode(["success" => false, "message" => "Błąd podczas usuwania zdjęcia."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Nieprawidłowe żądanie."]);
}

ob_end_flush();
?>