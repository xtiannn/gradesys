<?php
if (isset($_GET['studID'])) {
    $studID = filter_input(INPUT_GET, 'studID', FILTER_SANITIZE_NUMBER_INT);

    try {
        require_once "../includes/config.php";

        $stmt = $conn->prepare("SELECT * FROM students WHERE studID = :studID");
        $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');

        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Student not found.']);
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'An error occurred while fetching student information.']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No student ID provided.']);
}
?>
