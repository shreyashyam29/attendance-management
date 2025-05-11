<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "internship_db");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$usn  = $_GET['usn'] ?? '';
$isAdmin = isset($_GET['isAdmin']) && $_GET['isAdmin'] === 'true';

if (empty($from) || empty($to)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing date range"]);
    exit();
}

$query = "SELECT a.id, a.usn, a.status, a.login_time, s.photo_path" . ($isAdmin ? ", s.contact" : "") . " 
          FROM attendance a 
          JOIN students s ON a.usn = s.usn 
          WHERE a.login_time BETWEEN ? AND ?";

if (!empty($usn)) {
    $query .= " AND a.usn = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $from, $to, $usn);
} else {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $from, $to);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $entry = [
        "id" => $row["id"],
        "usn" => $row["usn"],
        "status" => $row["status"],
        "login_time" => $row["login_time"],
        "photo_path" => $row["photo_path"],
        "contact" => $isAdmin ? $row["contact"] : "N/A"
    ];
    $data[] = $entry;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
