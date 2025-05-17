<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["image"])) {
    echo json_encode(["status" => "error", "message" => "No image data received"]);
    exit;
}

$imageData = $data["image"];
$imageData = str_replace("data:image/png;base64,", "", $imageData);
$imageData = str_replace(" ", "+", $imageData);
$imageDecoded = base64_decode($imageData);

$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filePath = $uploadDir . "latest_image.png";
if (file_put_contents($filePath, $imageDecoded)) {
    echo json_encode(["status" => "success", "message" => "Image saved successfully", "path" => $filePath]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save image"]);
}
?>
