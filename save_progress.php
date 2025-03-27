<?php
header('Content-Type: application/json');

// Simple file-based storage (replace with database if needed)
$progressFile = 'progress.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $bookUrl = $data['bookUrl'] ?? '';
    $pagesRead = $data['pagesRead'] ?? 0;
    $totalPages = $data['totalPages'] ?? 0;
    $date = $data['date'] ?? date('Y-m-d');

    if (!$bookUrl) {
        echo json_encode(['success' => false, 'error' => 'No book URL provided']);
        exit;
    }

    $progressData = file_exists($progressFile) ? json_decode(file_get_contents($progressFile), true) : [];

    if (!isset($progressData[$bookUrl])) {
        $progressData[$bookUrl] = [
            'bookUrl' => $bookUrl,
            'pagesRead' => 0,
            'totalPages' => $totalPages,
            'startDate' => $date,
            'lastReadDate' => $date
        ];
    }

    $progressData[$bookUrl]['pagesRead'] = max($progressData[$bookUrl]['pagesRead'], $pagesRead);
    $progressData[$bookUrl]['totalPages'] = $totalPages;
    $progressData[$bookUrl]['lastReadDate'] = $date;

    file_put_contents($progressFile, json_encode($progressData));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>