<?php
require_once 'auth.php';
redirectIfNotLoggedIn();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

if (!isset($_FILES['file'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'No file uploaded']));
}

$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif',
    'video/mp4' => 'mp4',
    'video/quicktime' => 'mov', // Add more video types
    'audio/mpeg' => 'mp3',
    'audio/wav' => 'wav'
];


$file = $_FILES['file'];
$fileType = mime_content_type($file['tmp_name']);
$fileExt = $allowedTypes[$fileType] ?? null;

if (!$fileExt) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'File type not allowed']));
}

$maxSize = 10 * 1024 * 1024; // 10MB
if ($file['size'] > $maxSize) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'File too large']));
}

$uploadDir = '..//uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$safeName = uniqid() . '.' . $fileExt;
$targetPath = $uploadDir . $safeName;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // Determine message type
    if (strpos($fileType, 'image/') === 0) {
        $messageType = 'image';
    } elseif (strpos($fileType, 'video/') === 0) {
        $messageType = 'video';
    } else {
        $messageType = 'audio';
    }

    echo json_encode([
        'success' => true,
        'filePath' => 'uploads/' . $safeName,
        'fileType' => $messageType
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
}