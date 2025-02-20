<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $input = json_decode(file_get_contents('php://input'), true);
    $fileName = $input['fileName'];

    // Specify the backup directory
    $backupDir = '../../backup/';

    // Full path to the file
    $filePath = $backupDir . basename($fileName);

    // Check if the file exists and delete it
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            // File deleted successfully
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete the file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'File not found.']);
    }
} else {
    // Not a POST request
    http_response_code(405); // Method not allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
