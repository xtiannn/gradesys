<?php
require_once "../includes/config.php";
session_start();

$userID = $_SESSION['userID'];

try {
    $query = "SELECT * FROM notifications WHERE userID = :userID ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($notifications);
} catch (PDOException $e) {
    echo json_encode(['error' => 'An error occurred']);
}
?>
