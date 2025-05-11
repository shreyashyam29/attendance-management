<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "internship_db");

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "DB Connection failed"]);
    exit();
}

// Get JSON payload
$data = json_decode(file_get_contents("php://input"), true);

// Validate data
if (!isset($data['usn'], $data['status'], $data['login'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit();
}

// Prepare and execute statement
$sql = "INSERT INTO attendance (usn, status, login_time) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "SQL error: " . $conn->error]);
    exit();
}

$stmt->bind_param("sss", $data['usn'], $data['status'], $data['login']);

if ($stmt->execute()) {
    echo json_encode(["status" => "marked"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to mark attendance: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
