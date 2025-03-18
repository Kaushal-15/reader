<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "login_system";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "You must be logged in to view books."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT book_name, total_pages, pages_read, uploaded_at FROM books WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = [
        "title" => $row['book_name'],
        "total_pages" => $row['total_pages'],
        "pages_read" => $row['pages_read'],
        "start_date" => $row['uploaded_at']
    ];
}

echo json_encode(["status" => "success", "books" => $books]);

$stmt->close();
$conn->close();
?>
