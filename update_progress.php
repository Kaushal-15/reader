<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "readmind";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "You must be logged in to update progress."]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get the request body
$input = json_decode(file_get_contents('php://input'), true);
$book_id = isset($input['bookId']) ? (int)$input['bookId'] : 0;
$pages_read = isset($input['pagesRead']) ? (int)$input['pagesRead'] : 0;

if ($book_id <= 0 || $pages_read < 0) {
    echo json_encode(["status" => "error", "message" => "Invalid book ID or pages read."]);
    exit;
}

// Check if the book belongs to the user
$sql = "SELECT total_pages, pages_read FROM books WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $book_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Book not found or you do not have access to it."]);
    $stmt->close();
    $conn->close();
    exit;
}

$row = $result->fetch_assoc();
$total_pages = $row['total_pages'];
$current_pages_read = $row['pages_read'];

// Only update if the new pages_read is greater than the current value
if ($pages_read > $current_pages_read) {
    // Ensure pages_read does not exceed total_pages
    $pages_read = min($pages_read, $total_pages);

    $update_sql = "UPDATE books SET pages_read = ? WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iii", $pages_read, $book_id, $user_id);

    if ($update_stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Progress updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update progress."]);
    }

    $update_stmt->close();
} else {
    echo json_encode(["status" => "success", "message" => "No update needed."]);
}

$stmt->close();
$conn->close();
?>