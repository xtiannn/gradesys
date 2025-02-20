<?php
require_once "../includes/config.php";

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $start = $_POST['start'] ?? '';
    $end = $_POST['end'] ?? '';

    if ($title && $start && $end) {
        try {
            $sql = "INSERT INTO activities (title, description, event_start, event_end) VALUES (:title, :description, :start, :end)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':start', $start);
            $stmt->bindParam(':end', $end);
            $stmt->execute();

            $response['success'] = true;
            $response['message'] = 'Event created successfully.';
            $response['eventId'] = $conn->lastInsertId(); 
        } catch (PDOException $e) {
            $response['error'] = $e->getMessage();
        }
    } else {
        $response['error'] = 'ID and Title are required.';
    }
}

echo json_encode($response);
?>
