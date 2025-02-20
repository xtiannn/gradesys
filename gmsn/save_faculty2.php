<?php

require_once("includes/config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if it is a delete request
    if (isset($_POST['delete'])) {
        $curriculumID = $_POST['curriculumID'];
        $secID = $_POST['secID'];

        try {
            // Prepare the DELETE query
            $query = "DELETE FROM facultyAssign WHERE curriculumID = :curriculumID AND secID = :secID";
            $stmt = $conn->prepare($query);

            // Execute the statement
            $success = $stmt->execute([
                ':curriculumID' => $curriculumID,
                ':secID' => $secID
            ]);

            // Return a JSON response
            echo json_encode(['success' => $success]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit();
    }

    // Check if it is a save (insert/update) request
    if (isset($_POST['saveSubjectBtn'])) {
        $facultyID = $_POST['selFaculty'] ?? null;
        $curriculumID = $_POST['curriculumID'] ?? null;
        $gradelvlID = $_POST['gradelvlID'] ?? null;
        $semID = !empty($_POST['semID']) ? $_POST['semID'] : null;
        $programID = !empty($_POST['programID']) ? $_POST['programID'] : null;
        $secID = $_POST['secID'] ?? null;
        $secName = $_POST['secName'] ?? null;
        $subjectID = $_POST['subjectID'] ?? null;
        $subjectName = $_POST['subjectName'] ?? null;
        $deptID = $_POST['deptID'] ?? null;
        $ayID = $_POST['ayID'] ?? null;
        $ayName = $_POST['ayName'] ?? null;
    
        $selectedDays = $_POST['txtDay'] ?? [];
        $startTimes = $_POST['txtStartTime'] ?? [];
        $endTimes = $_POST['txtEndTime'] ?? [];
    
        try {
            // Get the facultyNum of the selected facultyID
            $queryFacultyNum = "SELECT facultyNum FROM faculty WHERE facultyID = :facultyID";
            $stmt = $conn->prepare($queryFacultyNum);
            $stmt->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
            $stmt->execute();
            $facultyNum = $stmt->fetchColumn();
    
            if ($facultyNum === false) {
                throw new Exception("Faculty ID not Found!!");
            }
    
            $schedules = [];
    
            foreach ($selectedDays as $index => $day) {
                if (isset($startTimes[$index]) && isset($endTimes[$index])) {
                    $startTime = date('h:i A', strtotime($startTimes[$index])); // Format start time
                    $endTime = date('h:i A', strtotime($endTimes[$index])); // Format end time
                    $schedules[] = "$day: $startTime-$endTime";
                }
            }
    
            // Implode the schedules array into a single string
            $scheduleString = implode(', ', $schedules);
    
            // Insert or update query for faculty assignment
            $query = 
            "INSERT INTO facultyAssign (facultyNum, facultyID, curriculumID, subjectID, secID, programID, gradelvlID, schedule, ayName, semID) 
            VALUES (:facultyNum, :facultyID, :curriculumID, :subjectID, :secID, :programID, :gradelvlID, :schedule, :ayName, :semID)
            ON DUPLICATE KEY UPDATE 
                facultyNum = VALUES(facultyNum), 
                facultyID = VALUES(facultyID), 
                subjectID = VALUES(subjectID), 
                secID = VALUES(secID), 
                curriculumID = VALUES(curriculumID),
                programID = VALUES(programID), 
                gradelvlID = VALUES(gradelvlID), 
                schedule = VALUES(schedule),
                ayName = VALUES(ayName),
                semID = VALUES(semID)";
    
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':facultyNum' => $facultyNum,
                ':facultyID' => $facultyID,
                ':curriculumID' => $curriculumID,
                ':subjectID' => $subjectID,
                ':secID' => $secID,
                ':programID' => $programID,
                ':gradelvlID' => $gradelvlID,
                ':schedule' => $scheduleString,
                ':ayName' => $ayName,
                ':semID' => $semID
            ]);
    
            // Send notification
            $notificationMessage = "You have been assigned to a new subject ($subjectName) for the current term.";
            $notificationQuery = "INSERT INTO notifications (userID, message, status, created_at) 
                                  VALUES (:userID, :message, 'unread', NOW())";
            $notificationStmt = $conn->prepare($notificationQuery);
            $notificationStmt->bindParam(':userID', $facultyNum, PDO::PARAM_INT);
            $notificationStmt->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
            $notificationStmt->execute();
    
            // Redirect based on success
            $redirectURL = "manage_sec.php?programID=$programID&gradelvlID=$gradelvlID&semID=$semID&secID=$secID&secName=$secName&deptID=$deptID&ayID=$ayID";
            header("Location: $redirectURL&operation=insert&status=success");
            exit();
    
        } catch (Exception $e) {
            // Output error details for debugging
            echo "Exception: " . $e->getMessage();
            exit();
        }
    }
    
    
    elseif (isset($_POST['updateSubjectBtn'])) {
        $facultyID = $_POST['selFaculty'] ?? null;
        $curriculumID = $_POST['curriculumID'] ?? null;
        $programID = $_POST['programID'] ?? null;
        $gradelvlID = $_POST['gradelvlID'] ?? null;
        $semID = $_POST['semID'] ?? null;
        $secID = $_POST['secID'] ?? null;
        $secName = $_POST['secName'] ?? null;
        $subjectID = $_POST['subjectID'] ?? null;
        $subjectName = $_POST['subjectName'] ?? null;
        $deptID = $_POST['deptID'] ?? null;
        $ayID = $_POST['ayID'] ?? null;
    
        // Retrieve selected days and times
        $selectedDays = $_POST['txtDay'] ?? [];
        $startTimes = $_POST['txtStartTime'] ?? [];
        $endTimes = $_POST['txtEndTime'] ?? [];
    
        try {
            // Get the facultyNum of the selected facultyID
            $queryFacultyNum = "SELECT facultyNum FROM faculty WHERE facultyID = :facultyID";
            $stmt = $conn->prepare($queryFacultyNum);
            $stmt->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
            $stmt->execute();
            $facultyNum = $stmt->fetchColumn();
    
            if ($facultyNum === false) {
                throw new Exception("Faculty ID not Found!!");
            }
    
            // Initialize query
            $query = "UPDATE facultyAssign SET facultyNum = :facultyNum, facultyID = :facultyID";
    
            // Check if days and times are provided
            if (!empty($selectedDays) && !empty($startTimes) && !empty($endTimes)) {
                // Initialize an array to hold the schedule strings
                $schedules = [];
    
                foreach ($selectedDays as $index => $day) {
                    // Check if start and end times are provided for this day
                    if (isset($startTimes[$index]) && isset($endTimes[$index])) {
                        $startTime = date('h:i A', strtotime($startTimes[$index])); // Format start time
                        $endTime = date('h:i A', strtotime($endTimes[$index])); // Format end time
                        // Create the schedule string for this day
                        $schedules[] = "$day: $startTime-$endTime";
                    }
                }
    
                // Implode the schedules array into a single string
                $scheduleString = implode(', ', $schedules);
    
                // Add schedule to query if days and times were provided
                $query .= ", schedule = :schedule";
            }
    
            // Complete the query
            $query .= " WHERE curriculumID = :curriculumID AND subjectID = :subjectID AND secID = :secID";
    
            // Prepare the statement
            $stmt = $conn->prepare($query);
    
            // Bind parameters
            $params = [
                ':facultyNum' => $facultyNum,
                ':facultyID' => $facultyID,
                ':curriculumID' => $curriculumID,
                ':subjectID' => $subjectID,
                ':secID' => $secID
            ];
    
            // Bind schedule if present
            if (!empty($scheduleString)) {
                $params[':schedule'] = $scheduleString;
            }
    
            // Execute the query
            $stmt->execute($params);
    
            // Send notification
            $notificationMessage = "You have been assigned to a new subject ($subjectName) for the current term.";
            $notificationQuery = "INSERT INTO notifications (userID, message, status, created_at) 
                                  VALUES (:userID, :message, 'unread', NOW())";
            $notificationStmt = $conn->prepare($notificationQuery);
            $notificationStmt->bindParam(':userID', $facultyNum, PDO::PARAM_INT);
            $notificationStmt->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
            $notificationStmt->execute();
    
            // Redirect based on success
            $redirectURL = "manage_sec.php?programID=$programID&gradelvlID=$gradelvlID&semID=$semID&secID=$secID&secName=$secName&deptID=$deptID&ayID=$ayID";
            header("Location: $redirectURL&operation=insert&status=success");
            exit();
    
        } catch (Exception $e) {
            // Output error details for debugging
            echo "Exception: " . $e->getMessage();
            exit();
        }
    }
    


    // elseif (isset($_POST['updateSubjectBtn'])) {
    //     $facultyID = $_POST['selFaculty'] ?? null;
    //     $curriculumID = $_POST['curriculumID'] ?? null;
    //     $programID = $_POST['programID'] ?? null;
    //     $gradelvlID = $_POST['gradelvlID'] ?? null;
    //     $semID = $_POST['semID'] ?? null;
    //     $secID = $_POST['secID'] ?? null;
    //     $secName = $_POST['secName'] ?? null;
    //     $subjectID = $_POST['subjectID'] ?? null;
    //     $subjectName = $_POST['subjectName'] ?? null;
    //     $deptID = $_POST['deptID'] ?? null;
    //     $ayID = $_POST['ayID'] ?? null;
    //     $facAssignID = $_POST['facAssignID'] ?? null;
    
    //     // Join selected days with commas
    //     $selectedDays = implode(',', $_POST['txtDay']) ?? null;
    //     $start = $_POST['txtStartTime'] ?? null;
    //     $end = $_POST['txtEndTime'] ?? null;
    
    //     try {

    //         $queryFacNum = "SELECT facultyNum FROM faculty WHERE facultyID = :facultyID";
    //         $stmtFacNum = $conn->prepare($queryFacNum);
    //         $stmtFacNum->bindParam('facultyID', $facultyID, PDO::PARAM_INT);
    //         $FacNum = $stmtFacNum->execute();

    //         $query = "UPDATE facultyAssign SET facultyID = :facultyID, day = :day, startTime = :startTime, endTime = :endTime, programID = :programID, gradelvlID = :gradelvlID
    //                     WHERE curriculumID = :curriculumID AND secID = :secID AND facultyAssignID = :facultyAssignID";
    //         $stmt = $conn->prepare($query);
    //         $stmt->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
    //         $stmt->bindParam(':day', $selectedDays, PDO::PARAM_STR);
    //         $stmt->bindParam(':startTime', $start, PDO::PARAM_STR);
    //         $stmt->bindParam(':endTime', $end, PDO::PARAM_STR);
    //         $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
    //         $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
    //         $stmt->bindParam(':curriculumID', $curriculumID, PDO::PARAM_INT);
    //         $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
    //         $stmt->bindParam(':facultyAssignID', $facAssignID, PDO::PARAM_INT);

    //         $result = $stmt->execute();
    
    //         // Notify the user about the update
    //         $notificationMessage = "Your subject assignment has been updated to $subjectName for the current term.";
    //         $notificationQuery = "INSERT INTO notifications (userID, message, status, created_at) 
    //                               VALUES (:userID, :message, 'unread', NOW())";
    //         $notificationStmt = $conn->prepare($notificationQuery);
    //         $notificationStmt->bindParam(':userID', $FacNum, PDO::PARAM_INT);
    //         $notificationStmt->bindParam(':message', $notificationMessage, PDO::PARAM_STR);
    //         $notificationStmt->execute();
    
    //         // Redirect based on success
    //         $redirectURL = "manage_sec.php?programID=$programID&gradelvlID=$gradelvlID&semID=$semID&secID=$secID&secName=$secName&deptID=$deptID&ayID=$ayID";
    //         header("Location: $redirectURL&operation=update&status=success");
    //         exit();
    
    //     } catch (Exception $e) {
    //         // Output error details for debugging
    //         echo "Exception: " . $e->getMessage();
    //         exit();
    //     }
    // }
    
    
}
?>
