<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "readmind";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "You must be logged in to upload books."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["bookFile"])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES["bookFile"];
    $book_name = $file["name"];
    $upload_dir = "uploads/";
    $file_path = $upload_dir . basename($book_name);
    $total_pages = 300; // Placeholder; replace with actual logic or form input later

    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

    if (move_uploaded_file($file["tmp_name"], $file_path)) {
        $sql = "INSERT INTO books (user_id, book_name, file_path, total_pages, pages_read) VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $user_id, $book_name, $file_path, $total_pages);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Book uploaded successfully!", "book_name" => $book_name]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to save book to database."]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload the file."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file uploaded."]);
}
$conn->close();
?>