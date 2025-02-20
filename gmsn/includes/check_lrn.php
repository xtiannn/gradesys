<?php
// check_lrn.php

require_once 'config.php'; 

header('Content-Type: application/json');

// Get the LRN from POST request
$data = json_decode(file_get_contents('php://input'), true);
$lrn = $data['lrn'];

if (empty($lrn)) {
    echo json_encode(['status' => 'error', 'message' => 'LRN is required']);
    exit();
}

try {
    // Prepare and execute the query to check if the LRN exists
    $stmt = $conn->prepare('SELECT COUNT(*) FROM students WHERE lrn = :lrn');
    $stmt->bindParam(':lrn', $lrn, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo json_encode(['status' => 'exists', 'message' => 'The LRN you entered is already in the database.']);
    } else {
        echo json_encode(['status' => 'available', 'message' => 'LRN is available.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>
