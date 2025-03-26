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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["photo"])) {
    $gallery = $_POST["gallery"] ?? '';
    $caption = $_POST["caption"] ?? '';
    $baseDir = $_SERVER['DOCUMENT_ROOT'] . "/gallery images/";
    $targetDir = $baseDir . $gallery . "/";
    $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
    $targetFile = $targetDir . $fileName;
    $fileType = mime_content_type($_FILES["photo"]["tmp_name"]);
    $allowedTypes = ["image/jpeg", "image/png", "image/gif"];

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
    if ($_FILES["photo"]["size"] > 5000000) {
        echo json_encode(["success" => false, "message" => "Plik jest za duży. Maksymalny rozmiar to 5MB."]);
        ob_end_flush();
        exit();
    }

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
        if (!empty($caption)) {
            $captions[$gallery][$fileName] = $caption;
            file_put_contents($captionsFile, json_encode($captions, JSON_PRETTY_PRINT));
        }
        echo json_encode(["success" => true, "message" => "Zdjęcie zostało przesłane pomyślnie."]);
    } else {
        echo json_encode(["success" => false, "message" => "Błąd podczas przesyłania zdjęcia."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Nieprawidłowe żądanie."]);
}

ob_end_flush();
?>