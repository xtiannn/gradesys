<?php
require_once "../includes/config.php";

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';

    if ($id) {
        try {
            $sql = "DELETE FROM activities WHERE id = :id"; 
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $response['success'] = true;
            $response['message'] = 'Event deleted successfully.';
        } catch (PDOException $e) {
            $response['error'] = $e->getMessage();
        }
    } else {
        $response['error'] = 'Event ID is required.';
    }
}

echo json_encode($response);
?>
