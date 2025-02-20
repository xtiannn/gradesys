<?php
require_once "../includes/config.php";

$events = [];

try {
    $sql = "SELECT id, title, event_start AS start, event_end AS end, description FROM activities"; 
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($events)) {
        echo json_encode(['message' => 'No events found.']);
    } else {
        echo json_encode($events);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
?>
