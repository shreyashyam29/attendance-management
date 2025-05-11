<?php
// Enable errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "internship_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filters
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$usn  = $_GET['usn'] ?? '';

// Validate
if (empty($from) || empty($to)) {
    die("Missing date filters.");
}

// Prepare query
$query = "SELECT * FROM attendance WHERE login_time BETWEEN ? AND ?";
if (!empty($usn)) {
    $query .= " AND usn = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $from, $to, $usn);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $from, $to);
}

$stmt->execute();
$result = $stmt->get_result();

// Send CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="attendance_export.csv"');

// Write to output
$output = fopen("php://output", "w");
fputcsv($output, ['ID', 'USN', 'Status', 'Login Time']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['usn'],
        $row['status'],
        $row['login_time']
    ]);
}

fclose($output);
$stmt->close();
$conn->close();
exit;
?>
