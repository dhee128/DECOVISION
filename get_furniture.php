<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Get the selected room type from frontend
$roomType = $_GET['roomType'];  

// Connect to MySQL database
$conn = new mysqli("localhost", "root", "", "decovision");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

// Fetch furniture data for the selected room type
$query = "SELECT name, image, price, link FROM furniture WHERE room_type='$roomType'";
$result = $conn->query($query);

$furniture = [];
while ($row = $result->fetch_assoc()) {
    $furniture[] = $row;
}

// Send JSON response
echo json_encode(["status" => "success", "data" => $furniture]);
$conn->close();
?>
