<?php
// Database connection
$host = "localhost";
$user = "root";
$password = ""; // Default XAMPP password (empty)
$database = "decovision_db";

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form fields are set before accessing them
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate that all fields are filled
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Prepare SQL query to prevent SQL injection
        $sql = "INSERT INTO contact_queries (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            echo "<script>alert('Message Sent Successfully!'); window.location.href='navhome.html';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
    }
}

// Close database connection
$conn->close();
?>
