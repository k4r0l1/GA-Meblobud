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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["image"])) {  // Changed "delete" to "image" to match your JS
    $gallery = $_POST["category"] ?? '';  // Changed "gallery" to "category" to match your JS
    $file = $_POST["image"] ?? '';
    $filePath = $_SERVER['DOCUMENT_ROOT'] . "/gallery images/" . $gallery . "/" . $file;

    error_log("Delete attempt: filePath=$filePath");

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            if (isset($captions[$gallery][$file])) {
                unset($captions[$gallery][$file]);
                if (empty($captions[$gallery])) unset($captions[$gallery]);
                file_put_contents($captionsFile, json_encode($captions, JSON_PRETTY_PRINT));
            }
            error_log("Delete succeeded: $filePath");
            echo json_encode(["success" => true, "message" => "Zdjęcie zostało usunięte."]);
        } else {
            error_log("Delete failed: " . error_get_last()['message']);
            echo json_encode(["success" => false, "message" => "Błąd podczas usuwania zdjęcia: " . error_get_last()['message']]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Plik nie istnieje."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Nieprawidłowe żądanie."]);
}

ob_end_flush();
?>