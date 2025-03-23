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
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['x'], $data['y'], $data['content'], $data['bookUrl'])) {
    $note_id = $data['id'];
    $x = $data['x'];
    $y = $data['y'];
    $content = $data['content'];
    $book_url = $data['bookUrl'];

    $sql = "INSERT INTO notes (user_id, book_url, note_id, x, y, content) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issdds", $user_id, $book_url, $note_id, $x, $y, $content);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to save note"]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid data"]);
}

$conn->close();
?>