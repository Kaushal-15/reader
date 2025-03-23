<?php
session_start();

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "readmind";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["fullName"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    if (empty($full_name) || empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if ($hashed_password === false) {
        echo json_encode(["status" => "error", "message" => "Password hashing failed."]);
        exit;
    }

    $check_email = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed (check email): " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists!"]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed (insert): " . $conn->error]);
        exit;
    }

    $stmt->bind_param("sss", $full_name, $email, $hashed_password);
    if ($stmt->execute()) {
        // Set session variable for the new user
        $user_id = $stmt->insert_id;
        $_SESSION['user_id'] = $user_id;
        echo json_encode(["status" => "success", "message" => "Account created successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error creating account: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>