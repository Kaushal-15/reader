<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "login_system";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check user credentials
    $sql = "SELECT email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_email, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            echo json_encode(["status" => "success", "message" => "Login successful!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid password!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Email not found!"]);
    }

    $stmt->close();
}

$conn->close();
?>
