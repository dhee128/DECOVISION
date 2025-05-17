<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "decovision_db");

// Check if image is uploaded
if (!isset($_FILES['roomImage']) || $_FILES['roomImage']['error'] != 0) {
    echo json_encode(["error" => "Please upload an image!"]);
    exit;
}

$room_type = $_POST['roomType'];
$image_name = $_FILES['roomImage']['name'];
$upload_dir = "uploads/";
$upload_file = $upload_dir . basename($image_name);

// Move uploaded file to the folder
if (!move_uploaded_file($_FILES['roomImage']['tmp_name'], $upload_file)) {
    echo json_encode(["error" => "Failed to upload image!"]);
    exit;
}

// 🔹 Basic Image Analysis Logic (Detect Room Type from Image Name)
if (strpos($image_name, "bed") !== false) {
    $room_type = "bedroom";
} elseif (strpos($image_name, "sofa") !== false) {
    $room_type = "living_room";
} elseif (strpos($image_name, "kitchen") !== false) {
    $room_type = "kitchen";
}

// 🔹 Fetch Furniture Suggestions Based on Room Type
$sql = "SELECT name, image, price, link FROM furniture WHERE room_type = '$room_type'";
$result = $conn->query($sql);

$furniture = [];
while ($row = $result->fetch_assoc()) {
    $furniture[] = $row;
}

echo json_encode($furniture);
?>
