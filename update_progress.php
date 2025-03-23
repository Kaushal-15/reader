<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "readmind";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['book_id'], $data['pages_read'])) {
    $book_id = $data['book_id'];
    $pages_read = $data['pages_read'];

    $sql = "UPDATE books SET pages_read = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $pages_read, $book_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Progress updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update progress"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
}

$conn->close();
?>