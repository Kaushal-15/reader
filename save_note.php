<?php
header('Content-Type: application/json');
require 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$noteId = $data['id'];
$x = $data['x'];
$y = $data['y'];
$content = $data['content'];
$bookUrl = $data['bookUrl'];

try {
    $stmt = $pdo->prepare("INSERT INTO notes (id, x, y, content, book_url) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$noteId, $x, $y, $content, $bookUrl]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>