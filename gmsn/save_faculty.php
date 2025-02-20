<?php

require_once("includes/config.php");

if (isset($_POST['saveFacultyBtn'])) {

    $id = $_POST['txtFacultyNumber'];
    $lname = $_POST['txtFacultylname'];
    $fname = $_POST['txtFacultyfname'];
    $mname = $_POST['txtFacultymname'];
    $gender = $_POST['txtFacultyGender'];
    $number = "0" . ($_POST['txtFacultyContactNum']);
    $email = $_POST['txtemail'];
    $pw = $_POST['txtPassword'];
    $cpw = $_POST['txtConfirmPassword'];
    $type = 2;

    if ($cpw == $pw) {
        $hashed_password = password_hash($pw, PASSWORD_DEFAULT);
    
        $query1 = "INSERT INTO faculty (facultyNum, lname, fname, mname, gender, contact, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt1 = $conn->prepare($query1);
        $result1 = $stmt1->execute([$id, $lname, $fname, $mname, $gender, $number, $email, $hashed_password]);
    
        $lastInsertId = $conn->lastInsertId();
    
        $query2 = "INSERT INTO users (facultyID, userTypeID, userID, lname, fname, mname, gender, contact, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt2 = $conn->prepare($query2);
        $result2 = $stmt2->execute([$lastInsertId, $type, $id, $lname, $fname, $mname, $gender, $number, $email, $hashed_password]);
        
        if ($result1 && $result2) {
            echo '<script>alert("Faculty Registered");</script>';
            header('Location: faculty.php');
            exit; 
        } else {
            echo '<script>alert("Error: Faculty Not Saved. Please try again later."); window.location.href = "faculty.php";</script>';
        }
    } else {
        echo '<script>alert("Error: Passwords do not match.");</script>';
    }
} 

elseif (isset($_POST['saveUserBtn'])) {
    require_once("includes/config.php");

    // Initialize variables
    $id = $_POST['txtuserID'] ?? NULL;
    $type = $_POST['selType'] ?? NULL;
    $lname = ucwords(strtolower(trim($_POST['txtuserlname'] ?? '')));
    $fname = ucwords(strtolower(trim($_POST['txtuserfname'] ?? '')));
    $mname = ucwords(strtolower(trim($_POST['txtusermname'] ?? NULL)));
    $gender = $_POST['selGender'] ?? '';
    $number = trim($_POST['txtcontact'] ?? NULL);
    $number = '0' . $number;
    $email = strtolower(trim($_POST['txtemail'] ?? ''));

    $defaultPass = $_POST['defaultPass'] ?? NULL;
    $status = $_POST['status'] ?? NULL;


    $photoPath = null;
    $uploadDir = '../uploads/';

    if (isset($_FILES['userPhoto']) && $_FILES['userPhoto']['error'] == UPLOAD_ERR_OK){
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); 
        }
        $photoTmpPath = $_FILES['userPhoto']['tmp_name'];
        $photoName = $_FILES['userPhoto']['name'];
        $photoExtension = pathinfo($photoName, PATHINFO_EXTENSION);
        $photoNewName = uniqid() . '.' . $photoExtension;
        $photoUploadPath = $uploadDir . $photoNewName;

        if (move_uploaded_file($photoTmpPath, $photoUploadPath)) {
            $photoPath = $photoUploadPath;
        }
    }

                                
    if($defaultPass == "on"){
        $isDefault = 1;
        $pw = $number;
        $cpw = $number;
    }else{
        $pw = $_POST['userPassword'] ?? '';
        $cpw = $_POST['userConPassword'] ?? '';
        $isDefault = 0;
    }


    if ($cpw == $pw) {
        $hashed_password = password_hash($pw, PASSWORD_DEFAULT);

        try {
            // Check if userID already exists
            $checkQuery = "SELECT COUNT(*) FROM users WHERE userID = :userID";
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bindParam(':userID', $id, PDO::PARAM_STR);
            $checkStmt->execute();
            $userExists = $checkStmt->fetchColumn();

            if ($userExists) {
                header('Location: users.php?status=duplicate_userID');
                exit;
            }

            $conn->beginTransaction();

            // Insert user data into the users table
            $query = "INSERT INTO users (userTypeID, userID, lname, fname, mname, gender, contact, email, password, photo, isDefault, isActive) 
                      VALUES (:userTypeID, :userID, :lname, :fname, :mname, :gender, :contact, :email, :password, :photo, :isDefault, :isActive)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':userTypeID', $type, PDO::PARAM_INT);
            $stmt->bindParam(':userID', $id, PDO::PARAM_STR);
            $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
            $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
            $stmt->bindParam(':mname', $mname, PDO::PARAM_STR);
            $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
            $stmt->bindParam(':contact', $number, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':photo', $photoPath, PDO::PARAM_STR);
            $stmt->bindParam(':isDefault', $isDefault, PDO::PARAM_INT);
            $stmt->bindParam(':isActive', $status, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            $lastInsertId = $conn->lastInsertId();

            if ($type == 2) {
                // Insert faculty data into the faculty table if userTypeID is 2
                $queryFaculty = "INSERT INTO faculty (facultyID, userTypeID, facultyNum, lname, fname, mname, gender, contact, email, password, photo, isActive) 
                                 VALUES (:facultyID, :userTypeID, :facultyNum, :lname, :fname, :mname, :gender, :contact, :email, :password, :photo, :isActive)";
                $stmtFaculty = $conn->prepare($queryFaculty);
                $stmtFaculty->bindParam(':facultyID', $lastInsertId, PDO::PARAM_INT);
                $stmtFaculty->bindParam(':userTypeID', $type, PDO::PARAM_INT);
                $stmtFaculty->bindParam(':facultyNum', $id, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':lname', $lname, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':fname', $fname, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':mname', $mname, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':gender', $gender, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':contact', $number, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':email', $email, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':photo', $photoPath, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':isActive', $status, PDO::PARAM_STR);

                $resultFaculty = $stmtFaculty->execute();
                
                if (!$resultFaculty) {
                    $conn->rollBack();
                    header('Location: users.php?status=error');
                    exit;
                }
            }

            if ($result) {
                $conn->commit();
                header('Location: users.php?status=success');
            } else {
                $conn->rollBack();
                header('Location: users.php?status=error');
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            error_log("Error inserting user: " . $e->getMessage());
            header('Location: users.php?status=error');
        }
        exit;
    } else {
        header('Location: users.php?status=password_error');
        exit;
    }
}



elseif (isset($_POST['updateFacultyBtn'])) {
    require_once("includes/config.php");

    $requiredFields = ['txtuserID', 'selType', 'txtuserlname', 'txtuserfname', 'selGender', 'txtcontact', 'txtemail'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }

    if (!empty($missingFields)) {
        header('Location: users.php?updstatus=missing_fields');
        exit;
    }


    $user_id = $_POST['uid'];
    $sqlUser = "SELECT photo FROM users WHERE uid = :uid";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtUser->execute();
    $row = $stmtUser->fetch(PDO::FETCH_ASSOC);
    $existingPhotoPath = $row['photo'];


    $id = $_POST['txtuserID'] ?? NULL;
    $type = $_POST['selType'] ?? NULL;
    $lname = $_POST['txtuserlname'] ?? NULL;
    $fname = $_POST['txtuserfname'] ?? NULL;
    $mname = $_POST['txtusermname'] ?? NULL;
    $gender = $_POST['selGender'] ?? NULL;
    $number = $_POST['txtcontact'] ?? NULL;
    $email = $_POST['txtemail'] ?? NULL;
    $pw = $_POST['userPassword'] ?? NULL;
    $cpw = $_POST['userConPassword'] ?? NULL;
    $uid = $_POST['uid']; // hidden input from form
    $existingPassword = $_POST['existingPassword'] ?? NULL;


    $photoPath = $existingPhotoPath;  
    $uploadDir = '../uploads/';
    
    if (isset($_FILES['userPhoto']) && $_FILES['userPhoto']['error'] == UPLOAD_ERR_OK) {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create the directory if it does not exist
        }
        $photoTmpPath = $_FILES['userPhoto']['tmp_name'];
        $photoName = $_FILES['userPhoto']['name'];
        $photoExtension = pathinfo($photoName, PATHINFO_EXTENSION);
        $photoNewName = uniqid() . '.' . $photoExtension;
        $photoUploadPath = $uploadDir . $photoNewName;

        if (move_uploaded_file($photoTmpPath, $photoUploadPath)) {
            $photoPath = $photoUploadPath;
        }
    }

    if ($cpw == $pw) {

        if (!empty($pw) && $cpw == $pw) {
            $hashed_password = password_hash($pw, PASSWORD_DEFAULT);
        } else {
            $hashed_password = $existingPassword; 
        }
        
        $conn->beginTransaction();

        try {
            // Prepare the common SQL query
            $query = "UPDATE users SET userTypeID = :userTypeID, userID = :userID, lname = :lname, fname = :fname, mname = :mname, gender = :gender, contact = :contact, email = :email, password = :password, photo = :photo WHERE uid = :uid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':userTypeID', $type, PDO::PARAM_INT);
            $stmt->bindParam(':userID', $id, PDO::PARAM_STR);
            $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
            $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
            $stmt->bindParam(':mname', $mname, PDO::PARAM_STR);
            $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
            $stmt->bindParam(':contact', $number, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':photo', $photoPath, PDO::PARAM_STR);
            $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
            
            $result = $stmt->execute();

            // If the user is a faculty, also update the faculty table
            if ($type == 2) {
                $queryFaculty = "UPDATE faculty SET userTypeID = :userTypeID, facultyNum = :facultyNum, lname = :lname, fname = :fname, mname = :mname, gender = :gender, contact = :contact, email = :email, password = :password, photo = :photo WHERE facultyID = :uid";
                $stmtFaculty = $conn->prepare($queryFaculty);
                $stmtFaculty->bindParam(':userTypeID', $type, PDO::PARAM_INT);
                $stmtFaculty->bindParam(':facultyNum', $id, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':lname', $lname, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':fname', $fname, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':mname', $mname, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':gender', $gender, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':contact', $number, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':email', $email, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':photo', $photoPath, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':uid', $uid, PDO::PARAM_INT);
                
                $resultFaculty = $stmtFaculty->execute();
                
                if (!$resultFaculty) {
                    $conn->rollBack();
                    header('Location: users.php?updstatus=error');
                    exit;
                }
            }

            if ($result) {
                $conn->commit();
                header('Location: users.php?updstatus=success');
            } else {
                $conn->rollBack();
                header('Location: users.php?updstatus=error');
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            if ($e->getCode() == 1062) { // Duplicate entry error code
                header('Location: users.php?updstatus=duplicate_userID');
            } else {
                error_log("Error updating user: " . $e->getMessage());
                header('Location: users.php?updstatus=error');
            }
        }
        exit;
    } else {
        header('Location: users.php?updstatus=password_error');
        exit;
    }
}
elseif (isset($_POST['updateProfileBtn'])) {

    $uTypeID = $_POST['uTypeID'];

    $goTo = '../faculty/index.php';

    $requiredFields = ['txtuserlname', 'txtuserfname', 'selGender', 'txtcontact', 'txtemail'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }
    if (!empty($missingFields)) {
        header("Location: $goTo?updProfstatus=missing_fields");
        exit;
    }


    $user_id = $_POST['uid'];
    $sqlUser = "SELECT photo, contact FROM users WHERE uid = :uid";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtUser->execute();
    $row = $stmtUser->fetch(PDO::FETCH_ASSOC);
    $existingPhotoPath = $row['photo'];



    $id = $_POST['txtuserID'] ?? NULL;
    $type = $_POST['selType'] ?? NULL;
    $lname = $_POST['txtuserlname'] ?? NULL;
    $fname = $_POST['txtuserfname'] ?? NULL;
    $mname = $_POST['txtusermname'] ?? NULL;
    $gender = $_POST['selGender'] ?? NULL;
    $number = $_POST['txtcontact'] ?? NULL;
    $email = $_POST['txtemail'] ?? NULL;
    $pw = $_POST['userPassword'] ?? NULL;
    $cpw = $_POST['userConPassword'] ?? NULL;
    $existingPassword = $_POST['existingPassword'] ?? NULL;
    $existingContact = $_POST['existingContact'] ?? NULL;
    $uid = $_POST['uid']; // hidden input from form

    $photoPath = $existingPhotoPath;  
    $uploadDir = '../uploads/';
    
    if (isset($_FILES['userPhoto']) && $_FILES['userPhoto']['error'] == UPLOAD_ERR_OK) {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create the directory if it does not exist
        }
        $photoTmpPath = $_FILES['userPhoto']['tmp_name'];
        $photoName = $_FILES['userPhoto']['name'];
        $photoExtension = pathinfo($photoName, PATHINFO_EXTENSION);
        $photoNewName = uniqid() . '.' . $photoExtension;
        $photoUploadPath = $uploadDir . $photoNewName;

        if (move_uploaded_file($photoTmpPath, $photoUploadPath)) {
            $photoPath = $photoUploadPath;
        }
    }

    if ($cpw == $pw) {

        if (!empty($pw) && $cpw == $pw) {
            $hashed_password = password_hash($pw, PASSWORD_DEFAULT);

            if (password_verify($existingContact, $pw)) {
                $isDefault = 1;
            } else {
                $isDefault = 0;
            }

        } else {
            $hashed_password = $existingPassword; 
        }

        $conn->beginTransaction();

        try {
            // Prepare the common SQL query
            $query = "UPDATE users SET isDefault = :isDefault, userTypeID = :userTypeID, userID = :userID, lname = :lname, fname = :fname, mname = :mname, gender = :gender, contact = :contact, email = :email, password = :password, photo = :photo WHERE uid = :uid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':isDefault', $isDefault, PDO::PARAM_INT);
            $stmt->bindParam(':userTypeID', $type, PDO::PARAM_INT);
            $stmt->bindParam(':userID', $id, PDO::PARAM_STR);
            $stmt->bindParam(':lname', $lname, PDO::PARAM_STR);
            $stmt->bindParam(':fname', $fname, PDO::PARAM_STR);
            $stmt->bindParam(':mname', $mname, PDO::PARAM_STR);
            $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
            $stmt->bindParam(':contact', $number, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(':photo', $photoPath, PDO::PARAM_STR);
            $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
            
            $result = $stmt->execute();

            // If the user is a faculty, also update the faculty table
            if ($type == 2) {
                $queryFaculty = "UPDATE faculty SET userTypeID = :userTypeID, facultyNum = :facultyNum, lname = :lname, fname = :fname, mname = :mname, gender = :gender, contact = :contact, email = :email, password = :password, photo = :photo WHERE facultyID = :uid";
                $stmtFaculty = $conn->prepare($queryFaculty);
                $stmtFaculty->bindParam(':userTypeID', $type, PDO::PARAM_INT);
                $stmtFaculty->bindParam(':facultyNum', $id, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':lname', $lname, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':fname', $fname, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':mname', $mname, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':gender', $gender, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':contact', $number, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':email', $email, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':password', $hashed_password, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':photo', $photoPath, PDO::PARAM_STR);
                $stmtFaculty->bindParam(':uid', $uid, PDO::PARAM_INT);
                
                $resultFaculty = $stmtFaculty->execute();
                
                if (!$resultFaculty) {
                    $conn->rollBack();
                    header("Location: $goTo?updProfstatus=error");
                    exit;
                }
            }

            if ($result) {
                $conn->commit();
                header("Location: $goTo?updProfstatus=success");
            } else {
                $conn->rollBack();
                header("Location: $goTo?updProfstatus=error");
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            if ($e->getCode() == 1062) { // Duplicate entry error code
                header("Location: $goTo?updProfstatus=duplicate_userID");
            } else {
                error_log("Error updating user: " . $e->getMessage());
                header("Location: $goTo?updProfstatus=error");
            }
        }
        exit;
    } else {
        header("Location: $goTo?updstatus=password_error");
        exit;
    }
}


if (isset($_POST['saveFacListBtn'])) {
    $selectedFacultyID = $_POST['selFaculty'];
    $days = $_POST['txtDay'];
    $start = $_POST['txtStartTime'];
    $end = $_POST['txtEndTime'];

    foreach ($_POST['selSub'] as $sub) {
        $query = "INSERT INTO faculty_list (facultyID, subjectID, day, starttime, endtime) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $result = $stmt->execute([$selectedFacultyID, $sub, $days, $start, $end]);

        if (!$result) {
            echo '<script>alert("Error: Faculty Not Saved. Please try again later."); window.location.href = "faculty_list.php";</script>';
            exit;
        }
    }

    echo '<script>alert("Faculty Registered");</script>';
    header('Location: faculty_list.php');
    exit; 
} else {
    echo '<script>alert("Error: Form data not submitted.")</script>';
}


if (isset($_POST['saveSubjectBtn'])) {
    $facultyID = $_POST['selFaculty'];
    $curriculumID = $_POST['curriculumID'];

    $programID = $_POST['programID'];
    $gradelvlID = $_POST['gradelvlID'];
    $semID = $_POST['semID'];

        try {
            $query = "UPDATE curriculum SET facultyID = ? WHERE curriculumID = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$facultyID, $curriculumID]);

            if ($stmt->rowCount() > 0) {
                
                $redirectURL = "manage_sec.php?programID=$programID&gradelvlID=$gradelvlID&semID=$semID";
                echo "<script>window.location.href = '$redirectURL';</script>";
                exit;
            } 
        } catch (PDOException $e) {
            echo '<script> alert("Error: ' . $e->getMessage() . '");</script>';
        }
} 




?>

