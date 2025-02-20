<?php 
    session_start();

    if (!isset($_SESSION['userID'])) {
        header('Location: ../logout.php');
        exit();
    }

    require_once "../includes/config.php";
    $userID = $_SESSION['userID']; 
    
    // Query to fetch faculty ID from facultyNum
    $sqlFac = "SELECT facultyID FROM faculty WHERE facultyNum = :facultyNum";
    $stmtFac = $conn->prepare($sqlFac);
    $stmtFac->bindParam(':facultyNum', $userID, PDO::PARAM_STR);
    $stmtFac->execute();
    
    $result = $stmtFac->fetch(PDO::FETCH_ASSOC);
    $facultyID = $result['facultyID'];

    // Set facultyID in session for later use
    $_SESSION['facultyID'] = $facultyID;

    // Fetch the active academic year name
    $sqlAy = "SELECT ayName, semID FROM academic_year WHERE isActive = 1 LIMIT 1";
    $stmtAy = $conn->prepare($sqlAy);
    $stmtAy->execute();
    $ayResult = $stmtAy->fetch(PDO::FETCH_ASSOC);
    $activeAyName = $ayResult['ayName'];
    $activeSemID = $ayResult['semID'];

    // Check if faculty ID is found
    if ($facultyID) {
        try {
            // Fetch all subject IDs assigned to the faculty
            $sqlSub = "SELECT s.subjectID, s.subjectName FROM facultyAssign f
                       JOIN subjects s ON f.subjectID = s.subjectID
                       WHERE f.facultyID = :facultyID";
            $stmtSub = $conn->prepare($sqlSub);
            $stmtSub->bindParam(':facultyID', $facultyID, PDO::PARAM_INT);
            $stmtSub->execute();
    
            $subjects = $stmtSub->fetchAll(PDO::FETCH_ASSOC);
    
            // Initialize an empty array to hold results
            $results = [];
    
            foreach ($subjects as $subject) {
                $subjectID = $subject['subjectID'];
                $subjectName = $subject['subjectName'];
    
                // Fetch ungraded students for the current subject, only for the active academic year
                $stmt = $conn->prepare("
                    SELECT 
                        st.lrn, 
                        st.lname, 
                        st.fname, 
                        st.mname, 
                        gl.gradelvlcode,
                        p.programcode,       
                        sec.secName,
                        ss.secID,
                        sec.deptID,
                        fa.facultyAssignID,
                        sec.ayName,
                        sec.semID     
                    FROM section_students ss
                    JOIN students st ON ss.studID = st.studID
                    JOIN facultyAssign fa ON ss.subjectID = fa.subjectID
                    LEFT JOIN student_grades sg ON ss.studID = sg.studID AND ss.subjectID = sg.subjectID
                    JOIN grade_level gl ON ss.gradelvlID = gl.gradelvlID
                    LEFT JOIN programs p ON ss.programID = p.programID  
                    JOIN sections sec ON ss.secID = sec.secID
                    WHERE ss.subjectID = :subjectID
                    AND (sg.grade IS NULL OR sg.grade = '')
                    AND (sec.ayName = :activeAyName AND ss.semID = :activeSemID)
                ");
    
                $stmt->bindParam(':subjectID', $subjectID, PDO::PARAM_INT);
                $stmt->bindParam(':activeAyName', $activeAyName, PDO::PARAM_STR);
                $stmt->bindParam(':activeSemID', $activeSemID, PDO::PARAM_STR);
                $stmt->execute();
                $ungradedStudents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                if ($ungradedStudents) {
                    $results[] = [
                        'subjectID' => $subjectID,        // Add subjectID for easier identification in JS
                        'subjectName' => $subjectName,
                        'ungraded_count' => count($ungradedStudents),  // Get the count of ungraded students
                        'ungraded_students' => $ungradedStudents  
                    ];
                }
            }
    
            // Return the results as JSON, including facultyID
            if (empty($results)) {
                header('Content-Type: application/json');
                echo json_encode([
                    'facultyID' => $facultyID,
                    'subjects' => []  // Return empty array if no ungraded students
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'facultyID' => $facultyID,
                    'subjects' => $results
                ]);
            }
    
        } catch (PDOException $e) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ERROR FETCHING Subjects: ' . $e->getMessage()]);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Faculty not found or session expired.']);
    }
?>
