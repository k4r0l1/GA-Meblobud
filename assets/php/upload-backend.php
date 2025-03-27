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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["images"])) {  // Changed "photo" to "images" to match your form
    $gallery = $_POST["category"] ?? '';  // Changed "gallery" to "category" to match your form
    $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/gallery images/";
    $targetDir = $baseDir . $gallery . "/";
    $fileName = time() . "_" . basename($_FILES["images"]["name"][0]);  // Assuming multiple files, take first
    $targetFile = $targetDir . $fileName;
    $fileType = mime_content_type($_FILES["images"]["tmp_name"][0]);
    $allowedTypes = ["image/jpeg", "image/png", "image/gif"];

    error_log("Upload attempt: gallery=$gallery, targetFile=$targetFile");

    if (!in_array($gallery, ["domki", "kuchnie", "sciany", "stolarka", "sufity", "tarasy"])) {
        echo json_encode(["success" => false, "message" => "Nieprawidłowa galeria."]);
        ob_end_flush();
        exit();
    }
    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(["success" => false, "message" => "Tylko pliki JPG, PNG lub GIF są dozwolone."]);
        ob_end_flush();
        exit();
    }
    if ($_FILES["images"]["size"][0] > 5000000) {
        echo json_encode(["success" => false, "message" => "Plik jest za duży. Maksymalny rozmiar to 5MB."]);
        ob_end_flush();
        exit();
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
        error_log("Created directory: $targetDir");
    }

    if (move_uploaded_file($_FILES["images"]["tmp_name"][0], $targetFile)) {
        error_log("Upload succeeded: $targetFile");
        echo json_encode(["success" => true, "message" => "Zdjęcie zostało przesłane pomyślnie."]);
    } else {
        error_log("Upload failed: " . error_get_last()['message']);
        echo json_encode(["success" => false, "message" => "Błąd podczas przesyłania zdjęcia: " . error_get_last()['message']]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Nieprawidłowe żądanie."]);
}

ob_end_flush();
?>