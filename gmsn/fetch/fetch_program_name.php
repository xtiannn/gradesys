<?php
include("../includes/config.php");

header('Content-Type: application/json');

$response = [
    'status' => 'error',
    'programName' => ''
];

if (isset($_POST['programID'])) {
    $programID = intval($_POST['programID']); 
    try {
        $sql = "SELECT programcode FROM programs WHERE programID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$programID]);
        $program = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($program) {
            $response = [
                'status' => 'success',
                'programName' => $program['programname']
            ];
        } else {
            $response['programName'] = 'Program not found';
        }
    } catch (Exception $e) {
        $response['programName'] = 'Error fetching program name';
    }
}

echo json_encode($response);
?>
