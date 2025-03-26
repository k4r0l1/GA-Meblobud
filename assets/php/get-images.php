<?php
header("Content-Type: application/json");
$gallery = $_GET["gallery"] ?? '';
$allowedGalleries = ["domki", "kuchnie", "sciany", "stolarka", "sufity", "tarasy"];
$dir = $_SERVER['DOCUMENT_ROOT'] . "/gallery images/" . $gallery . "/";

if (!in_array($gallery, $allowedGalleries) || !is_dir($dir)) {
    echo json_encode([]);
    exit();
}

$files = scandir($dir);
if ($files === false) {
    echo json_encode([]);
    exit();
}

$images = array_filter(array_diff($files, [".", ".."]), function($file) use ($dir) {
    $mime = mime_content_type($dir . $file);
    return in_array($mime, ["image/jpeg", "image/png", "image/gif", "image/jfif"]);
});
echo json_encode(array_values($images));
?>