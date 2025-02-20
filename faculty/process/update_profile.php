<?php

header('Content-Type: application/json');

// Include database connection
require_once("../includes/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize POST data
    $fname = isset($_POST['txtuserfname']) ? trim($_POST['txtuserfname']) : '';
    $lname = isset($_POST['txtuserlname']) ? trim($_POST['txtuserlname']) : '';
    $mname = isset($_POST['txtusermname']) ? trim($_POST['txtusermname']) : '';
    $email = isset($_POST['txtemail']) ? trim($_POST['txtemail']) : '';
    $gender = isset($_POST['selGender']) ? trim($_POST['selGender']) : '';
    $phone = isset($_POST['txtcontact']) ? trim($_POST['txtcontact']) : '';
    $password = isset($_POST['txtpass']) ? trim($_POST['txtpass']) : '';
    $confirmPassword = isset($_POST['txtconfirmpass']) ? trim($_POST['txtconfirmpass']) : '';
    $userID = isset($_POST['userID']) ? trim($_POST['userID']) : '';

    if($phone == $password){
        $isDefault = 1;
    }else{
        $isDefault = 0;
    }

    // Placeholder for password validation
    $errors = [];

    if ($password && $password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode(' ', $errors)
        ]);
        exit;
    }

    try {
        // Begin a transaction
        $conn->beginTransaction();

        // Prepare the update queries
        $updateUserQuery = "UPDATE users SET fname = :fname, lname = :lname, mname = :mname, email = :email, contact = :phone, gender = :gender, isDefault = :isDefault";
        $updateFacultyQuery = "UPDATE faculty SET fname = :fname, lname = :lname, mname = :mname, email = :email, contact = :phone, gender = :gender, isDefault = :isDefault";

        if (!empty($password)) {
            $updateUserQuery .= ", password = :password";
            $updateFacultyQuery .= ", password = :password";
        }

        $updateUserQuery .= " WHERE userID = :userID";
        $updateFacultyQuery .= " WHERE facultyNum = :userID";

        // Update the `users` table
        $stmt = $conn->prepare($updateUserQuery);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':isDefault', $isDefault);
        $stmt->bindParam(':userID', $userID);

        if (!empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $passwordHash);
        }

        $stmt->execute();

        // Update the `faculty` table
        $stmt2 = $conn->prepare($updateFacultyQuery);
        $stmt2->bindParam(':fname', $fname);
        $stmt2->bindParam(':lname', $lname);
        $stmt2->bindParam(':mname', $mname);
        $stmt2->bindParam(':email', $email);
        $stmt2->bindParam(':phone', $phone);
        $stmt2->bindParam(':gender', $gender);
        $stmt2->bindParam(':isDefault', $isDefault);
        $stmt2->bindParam(':userID', $userID);

        if (!empty($password)) {
            $stmt2->bindParam(':password', $passwordHash);
        }

        $stmt2->execute();

        // Commit the transaction
        $conn->commit();

        // Return success response
        $response = [
            'success' => true,
            'message' => 'Profile updated successfully.'
        ];
    } catch (PDOException $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();
        // Return error response
        $response = [
            'success' => false,
            'message' => 'Error updating profile: ' . $e->getMessage()
        ];
    }

    echo json_encode($response);
    exit;
}

// If the request method is not POST
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed']);
?>
