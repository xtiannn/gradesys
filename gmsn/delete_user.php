<?php
session_start();
error_log("Session userID: " . (isset($_SESSION['userID']) ? $_SESSION['userID'] : 'not set'));

function getLoggedInUserID() {
    return isset($_SESSION['userID']) ? $_SESSION['userID'] : null;
}

require_once("includes/config.php");

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $loggedInUserID = getLoggedInUserID();

    error_log("Logged in userID: " . $loggedInUserID . ", Attempting to delete userID: " . $user_id);

    $query_check = "SELECT userID FROM users WHERE id = :id";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt_check->execute();
    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $userID = $result['userID'];
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid userID']);
        exit;
    }

    error_log("Fetched userID: " . $userID . ", Comparing with loggedInUserID: " . $loggedInUserID);

    if ($userID == $loggedInUserID) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot delete your own account.']);
        exit;
    }

    // Start transaction
    $conn->beginTransaction();

    try {
        // Update user status in the 'users' table
        $query1 = "UPDATE users SET isActive = 0 WHERE id = :id";
        $stmt1 = $conn->prepare($query1);
        $stmt1->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt1->execute();

        // Update faculty status in the 'faculty' table
        $query2 = "UPDATE faculty SET isActive = 0 WHERE facultyNum = :userID";
        $stmt2 = $conn->prepare($query2);
        $stmt2->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt2->execute();

        // Commit transaction
        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        // Rollback transaction in case of error
        $conn->rollBack();
        error_log("Error updating user: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Failed to update user']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'userID not provided']);
}
?>
