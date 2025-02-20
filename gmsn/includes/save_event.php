<?php
require_once "config.php";

$response = ["status" => "error", "message" => "Invalid request"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? null;
    $start = $_POST['start'] ?? null;
    $end = $_POST['end'] ?? null;
    $id = $_POST['id'] ?? null;

    try {
        if (empty($title) || empty($start)) {
            throw new Exception("Title and start date are required.");
        }

        if ($id) {
            // Update existing event
            $sql = "UPDATE activities SET title = :title, event_start = :event_start, event_end = :event_end WHERE activity_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':event_start' => $start,
                ':event_end' => $end,
                ':id' => $id
            ]);
            $response = ["status" => "success", "message" => "Event updated successfully"];
        } else {
            // Insert new event
            $sql = "INSERT INTO activities (title, event_start, event_end) VALUES (:title, :event_start, :event_end)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':event_start' => $start,
                ':event_end' => $end
            ]);
            $response = ["status" => "success", "message" => "Event added successfully"];
        }
    } catch (Exception $e) {
        $response = ["status" => "error", "message" => $e->getMessage()];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
