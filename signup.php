<?php
// Database connection
$host = "localhost";
$username = "root"; // Default WAMP MySQL username
$password = "";     // Default WAMP MySQL password (empty)
$database = "login_system";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["fullName"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

    // Check if email already exists
    $check_email = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists!"]);
    } else {
        // Insert new user
        $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $full_name, $email, $password);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Account created successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error creating account."]);
        }
    }

    $stmt->close();
}

$conn->close();
?>
