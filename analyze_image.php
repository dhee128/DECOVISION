<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$imagePath = "uploads/latest_image.png"; 

if (!file_exists($imagePath)) {
    echo json_encode(["status" => "error", "message" => "Image not found"]);
    exit;
}

// 🔥 Basic AI Logic (Replace this with real analysis)
$roomType = "Living Room"; 

// **Dynamic Suggestions Based on Room Type**
$suggestions = [];

if ($roomType === "Living Room") {
    $suggestions = ["Sofa", "Table", "Lamp"];
} elseif ($roomType === "Bedroom") {
    $suggestions = ["Bed", "Wardrobe", "Nightstand"];
} elseif ($roomType === "Dining Room") {
    $suggestions = ["Dining Table", "Chairs", "Cabinet"];
} else {
    $suggestions = ["Chair", "Table", "Lamp"];
}

echo json_encode([
    "status" => "success",
    "roomType" => $roomType,
    "suggestions" => $suggestions
]);
?>
