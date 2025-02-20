<?php
require_once "../includes/config.php"; // Include the database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data from the request
    $id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    // Validate the input data
    if (empty($id) || empty($title)) {
        echo json_encode(['error' => 'ID and Title are required.']);
        exit;
    }

    try {
        // Prepare an SQL statement to update the event
        $sql = "UPDATE activities SET title = :title, description = :description WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Event updated successfully.']);
        } else {
            echo json_encode(['error' => 'Failed to update event.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
