<?php
session_start();

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/wamp64/logs/php_error.log');

header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$password = "";
$database = "readmind";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_url = $_GET['bookUrl'] ?? '';

if (empty($book_url)) {
    echo json_encode(["success" => false, "error" => "Book URL not provided"]);
    exit;
}

$sql = "SELECT note_id, x, y, content, page_num FROM notes WHERE user_id = ? AND book_url = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("is", $user_id, $book_url);
$stmt->execute();
$result = $stmt->get_result();

$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = [
        'id' => $row['note_id'],
        'x' => (float)$row['x'],
        'y' => (float)$row['y'],
        'content' => $row['content'],
        'pageNum' => (int)$row['page_num']
    ];
}

echo json_encode($notes);

$stmt->close();
$conn->close();
?>