<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "readmind";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed"]);
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

$sql = "SELECT note_id, x, y, content FROM notes WHERE user_id = ? AND book_url = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $book_url);
$stmt->execute();
$result = $stmt->get_result();

$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = [
        'id' => $row['note_id'],
        'x' => $row['x'],
        'y' => $row['y'],
        'content' => $row['content']
    ];
}

echo json_encode($notes);

$stmt->close();
$conn->close();
?>