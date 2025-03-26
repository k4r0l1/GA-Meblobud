<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    http_response_code(403);
    echo json_encode(["success" => false, "error" => "Nieautoryzowany dostęp"]);
    exit();
}

header("Content-Type: application/json");

$category = $_POST["category"] ?? '';
$updatedCaptions = $_POST["captions"] ?? [];

if (empty($category) || !in_array($category, ["domki", "kuchnie", "sciany", "stolarka", "sufity", "tarasy"]) || !is_array($updatedCaptions)) {
    echo json_encode(["success" => false, "error" => "Nieprawidłowe dane"]);
    exit();
}

$captionsFile = $_SERVER['DOCUMENT_ROOT'] . "/assets/data/captions.json";
$captions = file_exists($captionsFile) ? json_decode(file_get_contents($captionsFile), true) : [];
if ($captions === null) {
    $captions = [];
}

if (!isset($captions[$category])) {
    $captions[$category] = [];
}

foreach ($updatedCaptions as $image => $caption) {
    $caption = trim($caption);
    if ($caption === '') {
        unset($captions[$category][$image]); // Remove empty captions
    } else {
        $captions[$category][$image] = $caption;
    }
}

if (empty($captions[$category])) {
    unset($captions[$category]); // Remove category if no captions
}

file_put_contents($captionsFile, json_encode($captions, JSON_PRETTY_PRINT));
echo json_encode(["success" => true]);
?>