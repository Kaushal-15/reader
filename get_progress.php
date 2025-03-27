<?php
header('Content-Type: application/json');

$progressFile = 'progress.json';

if (file_exists($progressFile)) {
    $progressData = json_decode(file_get_contents($progressFile), true);
    echo json_encode(['success' => true, 'books' => array_values($progressData)]);
} else {
    echo json_encode(['success' => true, 'books' => []]);
}
?>