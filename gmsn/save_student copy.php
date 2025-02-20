<?php
require_once("includes/config.php");

if (isset($_POST['saveStudentBtn'])) {
    // Student's information
    $lname = strtoupper(trim($_POST['txtStudentlname']));
    $fname = strtoupper(trim($_POST['txtStudentfname']));
    $mname = strtoupper(trim($_POST['txtStudentmname']));
    $lrn = trim($_POST['txtStudentLRN']);
    $gender = trim($_POST['txtStudentGender']);
    $contact = trim($_POST['txtContactNum']);
    $dob = trim($_POST['dtDateOfBirth']);
    $pob = strtoupper(trim($_POST['txtPlaceOfBirth']));
    $nationality = strtoupper(trim($_POST['selNationality']));
    $age = trim($_POST['txtAge']);
    $language = strtoupper(trim($_POST['selLanguage']));

    if ($language == "other"){
        $language = strtoupper(trim($_POST['txtOtherLanguage']));
    }

    $religion = strtoupper(trim($_POST['selReligion']));

    // Address
    $house = strtoupper(trim($_POST['txtHouseNumber']));
    $st = strtoupper(trim($_POST['txtStreetName']));
    $brgy = strtoupper(trim($_POST['txtBarangay']));
    $subd = strtoupper(trim($_POST['txtSubdivision']));
    $city = strtoupper(trim($_POST['txtCity']));
    $province = strtoupper(trim($_POST['txtProvince']));
    $postal = trim($_POST['txtPostalCode']);

    $address = $house . ' ' . $st . ', ' . $subd . ', ' . $brgy . ', ' . $city . ', ' . $province . ', ' . $postal;

    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM students WHERE lrn = ?");
        $stmt->execute([$lrn]);
        if ($stmt->fetchColumn() > 0) {
            header("Location: manage_studentsRec.php?alert=lrn_exists");
            exit;
        }
    } catch (\Throwable $e) {
        echo '<script> alert("Error: ' . $e->getMessage() . '");</script>';
    }

    $targetDir = "uploads/photo/";  
    $fileName = basename($_FILES["txtPhoto"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    
    // Father's information
    if (isset($_POST['fatherNoRecord']) && $_POST['fatherNoRecord'] == 'on') {
        $faddress = "";
        $flname = "";
        $ffname = "";
        $fmname = "";
        $fage = "";
        $foccu = "";
        $feduc = "";
        $fcontact = "";
        $foffice = "";
        $femail = "";
    }else{
        $flname = strtoupper(trim($_POST['txtFatherlname']));
        $ffname = strtoupper(trim($_POST['txtFatherfname']));
        $fmname = strtoupper(trim($_POST['txtFathermname']));
        $fage = trim($_POST['txtFatherAge']);
        $foccu = strtoupper(trim($_POST['txtFatherOccupation']));
        $feduc = strtoupper(trim($_POST['txtFatherEducAtt']));
        $fcontact = trim($_POST['txtFatherContactNum']);
        $foffice = strtoupper(trim($_POST['txtFatherOffice']));
        $femail = trim($_POST['txtFatherEmail']);
        
        // Father's address
        if (isset($_POST['sameAddressCheckbox']) && $_POST['sameAddressCheckbox'] == 'on') {
            $faddress = $address; // Copy student's address
        } else {
            $fhouse = strtoupper(trim($_POST['txtfHouseNumber']));
            $fst = strtoupper(trim($_POST['txtfStreetName']));
            $fbrgy = strtoupper(trim($_POST['txtfBarangay']));
            $fsubd = strtoupper(trim($_POST['txtfSubdivision']));
            $fcity = strtoupper(trim($_POST['txtfCity']));
            $fprovince = strtoupper(trim($_POST['txtfProvince']));
            $fpostal = trim($_POST['txtfPostalCode']);
            $faddress = $fhouse . ' ' . $fst . ', ' . $fsubd . ', ' . $fbrgy . ', ' . $fcity . ', ' . $fprovince . ', ' . $fpostal;
        }
    }


    // Mother's information
    if (isset($_POST['motherNoRecord']) && $_POST['motherNoRecord'] == 'on') {
        $maddress = "";
        $mlname = "";
        $mfname = "";
        $mmname = "";
        $mage = "";
        $moccu = "";
        $meduc = "";
        $mcontact = "";
        $moffice = "";
        $memail = "";
    }else{
        $mlname = strtoupper(trim($_POST['txtMotherlname']));
        $mfname = strtoupper(trim($_POST['txtMotherfname']));
        $mmname = strtoupper(trim($_POST['txtMothermname']));
        $mage = trim($_POST['txtMotherAge']);
        $moccu = strtoupper(trim($_POST['txtMotherOccupation']));
        $meduc = strtoupper(trim($_POST['txtMotherEducAtt']));
        $mcontact = trim($_POST['txtMotherContactNum']);
        $moffice = strtoupper(trim($_POST['txtMotherOffice']));
        $memail = trim($_POST['txtMotherEmail']);
        
        // Mother's address
        if (isset($_POST['sameAddressCheckboxM']) && $_POST['sameAddressCheckboxM'] == 'on') {
            $maddress = $address; // Copy student's address
        } else {
            $mhouse = strtoupper(trim($_POST['txtmHouseNumber']));
            $mst = strtoupper(trim($_POST['txtmStreetName']));
            $mbrgy = strtoupper(trim($_POST['txtmBarangay']));
            $msubd = strtoupper(trim($_POST['txtmSubdivision']));
            $mcity = strtoupper(trim($_POST['txtmCity']));
            $mprovince = strtoupper(trim($_POST['txtmProvince']));
            $mpostal = strtoupper(trim($_POST['txtmPostalCode']));
            $maddress = $mhouse . ' ' . $mst . ', ' . $msubd . ', ' . $mbrgy . ', ' . $mcity . ', ' . $mprovince . ', ' . $mpostal;
        }
    }

    //Guardian's information
    $relationship = strtoupper(trim($_POST['selGuardian']));

    if ($relationship == "Mother") {
        $glname = $mlname;
        $gfname = $mfname;
        $gmname = $mmname;
        $gcontact = $mcontact;

        $gaddress = $maddress;
    }elseif ($relationship == "Father"){
        $glname = $flname;
        $gfname = $ffname;
        $gmname = $fmname;
        $gcontact = $fcontact;

        $gaddress = $faddress;
    }else{
        
        $glname = strtoupper(trim($_POST['txtGdlname']));
        $gfname = strtoupper(trim($_POST['txtGdfname']));
        $gmname = strtoupper(trim($_POST['txtGdmname']));
        $gcontact = trim($_POST['txtGdContactNum']);

        
        // Guardian's address
        if (isset($_POST['sameAddressCheckboxG']) && $_POST['sameAddressCheckboxG'] == 'on') {            
        $gaddress = $address; // Copy student's address
        } else {
            $ghouse = strtoupper(trim($_POST['txtGdHouseNumber']));
            $gst = strtoupper(trim($_POST['txtGdStreetName']));
            $gbrgy = strtoupper(trim($_POST['txtGdBarangay']));
            $gsubd = strtoupper(trim($_POST['txtGdSubdivision']));
            $gcity = strtoupper(trim($_POST['txtGdCity']));
            $gprovince = strtoupper(trim($_POST['txtGdProvince']));
            $gpostal = trim($_POST['txtGdPostalCode']);
            $gaddress = $ghouse . ' ' . $gst . ', ' . $gsubd . ', ' . $gbrgy . ', ' . $gcity . ', ' . $gprovince . ', ' . $gpostal;
        }
    }


    
    // Handle photo upload
    if (!empty($_FILES["txtPhoto"]["name"])) {
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["txtPhoto"]["tmp_name"], $targetFilePath)) {
                $photo = $fileName;
                header("Location: manage_studentsRec.php?alert=file_upload_success"); 
                exit;
            } else {
                header("Location: manage_studentsRec.php?alert=file_upload_error"); 
                exit;
            }
        } else {
                header("Location: manage_studentsRec.php?alert=invalid_file_type"); 
                exit;
        }
    } else {
        $photo = ""; 
    }

    // Save student information
    try {
        $sql = "INSERT INTO students (photo, lrn, lname, fname, mname, gender, dob, contact, pob, address, nationality, language, religion, age,
                    flname, ffname, fmname, fage, foccu, feduc, fcontact, foffice, femail, faddress,
                    mlname, mfname, mmname, mage, moccu, meduc, mcontact, moffice, memail, maddress,
                    glname, gfname, gmname, gcontact, gaddress, relationship) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,
                            ?,?,?,?,?,?,?,?,?,?,
                            ?,?,?,?,?,?,?,?,?,?,
                            ?,?,?,?,?,?)
                            ";
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute([
            $photo, $lrn, $lname, $fname, $mname, $gender, $dob, $contact, $pob, $address, $nationality, $language, $religion, $age,
            $flname, $ffname, $fmname, $fage, $foccu, $feduc, $fcontact, $foffice, $femail, $faddress,
            $mlname, $mfname, $mmname, $mage, $moccu, $meduc, $mcontact, $moffice, $memail, $maddress,
            $glname, $gfname, $gmname, $gcontact, $gaddress, $relationship
        ]);
        
        if ($result) {
            header("Location: manage_studentsRec.php?alert=student_added");
            exit;
        } else {
            header("Location: manage_studentsRec.php?alert=student_not_saved");
            exit;
        }
    } catch (PDOException $e) {
        echo '<script> alert("Error: ' . $e->getMessage() . '");</script>';
    }
}
?>
