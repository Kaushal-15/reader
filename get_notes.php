<?php
header('Content-Type: application/json');
require 'db_connect.php';

$bookUrl = $_GET['bookUrl'];

try {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE book_url = ?");
    $stmt->execute([$bookUrl]);
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($notes);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>