<?php
// Enable error display during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'internship_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$college = $_POST['college'];
$usn = $_POST['usn'];
$contact = $_POST['contact'];
$project = $_POST['project'];
$duration = $_POST['duration'];
$report_status = $_POST['report_status'];

// File upload
$target_dir = "uploads/";
$filename = basename($_FILES["photo"]["name"]);
$target_file = $target_dir . $filename;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// ✅ Validate file extension
if ($imageFileType !== "jpg" && $imageFileType !== "jpeg") {
  die("Only JPG images are allowed.");
}

// ✅ Validate MIME type
$check = getimagesize($_FILES["photo"]["tmp_name"]);
if ($check === false || $check['mime'] !== 'image/jpeg') {
  die("Uploaded file is not a valid JPG image.");
}

// ✅ Move uploaded file
if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
  die("Error uploading the image.");
}

// ✅ Save student info with photo path in DB
$sql = "INSERT INTO students (name, college, usn, contact, project, duration, report_status, photo_path)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $name, $college, $usn, $contact, $project, $duration, $report_status, $target_file);

if ($stmt->execute()) {
  echo "✅ Student added successfully with photo.";
} else {
  echo "❌ Database error: " . $stmt->error;
}
if (!is_dir("uploads")) {
  mkdir("uploads", 0755, true);
}

$stmt->close();
$conn->close();
?>
