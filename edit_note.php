<?php
header('Content-Type: application/json');
require 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$noteId = $data['id'];
$newContent = $data['content'];

try {
    $stmt = $pdo->prepare("UPDATE notes SET content = ? WHERE id = ?");
    $stmt->execute([$newContent, $noteId]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>