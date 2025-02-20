<?php
require_once "includes/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the student ID from the hidden input
    $studID = $_POST['studID'];

    // Retrieve other form data
    $lrn = $_POST['txtStudentLRN'];
    $lname = $_POST['txtStudentlname'];
    $fname = $_POST['txtStudentfname'];
    $mname = $_POST['txtStudentmname'];
    $gender = $_POST['txtStudentGender'];
    $dob = $_POST['dtDateOfBirth'];
    $age = $_POST['txtAge'];
    $pob = $_POST['txtPlaceOfBirth'];
    $contact = $_POST['txtContactNum'];
    $nationality = $_POST['selNationality'];
    $language = $_POST['selLanguage'];
    $religion = $_POST['selReligion'];

    // Handle photo upload
    $photo = $_FILES['txtPhoto'];
    $photoFilename = '';

    // Retrieve existing photo filename from the database if not updating
    try {
        $sqlGetPhoto = "SELECT photo FROM students WHERE studID = :studID";
        $stmtPhoto = $conn->prepare($sqlGetPhoto);
        $stmtPhoto->bindParam(':studID', $studID);
        $stmtPhoto->execute();
        $resultPhoto = $stmtPhoto->fetch(PDO::FETCH_ASSOC);
        $existingPhoto = $resultPhoto['photo'] ?? '';
    } catch (PDOException $e) {
        echo 'Database error (Photo Fetch): ' . $e->getMessage();
        exit();
    }

    if ($photo['error'] == UPLOAD_ERR_OK) {
        // Define the upload directory
        $uploadDir = 'uploads/photo/';
        $photoFilename = basename($photo['name']);
        $photoPath = $uploadDir . $photoFilename;

        // Move the uploaded file to the upload directory
        if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
            die('File upload error');
        }
    } else {
        $photoFilename = $existingPhoto;
    }

    // Handle address
    $addressCB = isset($_POST['keepAddressCB']) ? $_POST['keepAddressCB'] : '';
    if ($addressCB !== 'on') {
        $houseNumber = $_POST['txtHouseNumber'] ?? '';
        $streetName = $_POST['txtStreetName'] ?? '';
        $subdivision = $_POST['txtSubdivision'] ?? '';
        $region = $_POST['txtRegion'] ?? '';
        $province = $_POST['txtProvince'] ?? '';
        $city = $_POST['txtCity'] ?? '';
        $barangay = $_POST['txtBarangay'] ?? '';
        $postalCode = $_POST['txtPostalCode'] ?? '';

        $address = $houseNumber . ' ' . $streetName . ', ' . $subdivision . ', ' . $barangay . ', ' . $city . ', ' . $province . ', ' . $postalCode;
    } else {
        try {
            $sqlGetAddress = "SELECT address FROM students WHERE studID = :studID";
            $stmt = $conn->prepare($sqlGetAddress);
            $stmt->bindParam(':studID', $studID);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $address = $result['address'] ?? '';
        } catch (PDOException $e) {
            echo 'Database error (Address Fetch): ' . $e->getMessage();
            exit();
        }
    }

    // Prepare the SQL query for updating student information
    $sql = "UPDATE students SET
            lrn = :lrn,
            lname = :lname,
            fname = :fname,
            mname = :mname,
            gender = :gender,
            dob = :dob,
            age = :age,
            pob = :pob,
            contact = :contact,
            address = :address,
            nationality = :nationality,
            language = :language,
            religion = :religion,
            photo = :photo
        WHERE studID = :studID";

    try {
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':lrn', $lrn);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':pob', $pob);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':nationality', $nationality);
        $stmt->bindParam(':language', $language);
        $stmt->bindParam(':religion', $religion);
        $stmt->bindParam(':photo', $photoFilename);
        $stmt->bindParam(':studID', $studID);

        $stmt->execute();
    } catch (PDOException $e) {
        echo 'Database error (Student Update): ' . $e->getMessage();
        exit();
    }

    // Retrieve father's information from the form
    $flname = $_POST['txtFatherlname'] ?? null;
    $ffname = $_POST['txtFatherfname'] ?? null;
    $fmname = $_POST['txtFathermname'] ?? null;
    $fage = $_POST['txtFatherAge'] ?? null;
    $foccu = $_POST['txtFatherOccupation'] ?? null;
    $feduc = $_POST['txtFatherEducAtt'] ?? null;
    $fcontact = $_POST['txtFatherContactNum'] ?? null;
    $foffice = $_POST['txtFatherOffice'] ?? null;
    $femail = $_POST['txtFatherEmail'] ?? null;
    $fdob = $_POST['txtFatherDOB'] ?? null;

    // Check if 'fatherNoRecord' checkbox is checked
    $noFatherRecord = isset($_POST['fatherNoRecord']) ? $_POST['fatherNoRecord'] : '';

    if ($noFatherRecord === 'on') {
        $flname = $ffname = $fmname = $fage = $fdob = $foccu = $feduc = $fcontact = $foffice = $femail = NULL;
        $faddress = NULL; // Ensure father's address is also set to NULL
    } else {
        // Handle father's address
        $addressFatherCB = isset($_POST['keepFatherAddressCB']) ? $_POST['keepFatherAddressCB'] : '';
        if ($addressFatherCB !== 'on') {
            $fhouse = $_POST['txtfHouseNumber'] ?? '';
            $fstreet = $_POST['txtfStreetName'] ?? '';
            $fsubd = $_POST['txtfSubdivision'] ?? '';
            $fregion = $_POST['txtfRegion'] ?? '';
            $fprovince = $_POST['txtfProvince'] ?? '';
            $fcity = $_POST['txtfCity'] ?? '';
            $fbrgy = $_POST['txtfBarangay'] ?? '';
            $fzip = $_POST['txtfPostalCode'] ?? '';

            $faddress = $fhouse . ' ' . $fstreet . ', ' . $fsubd . ', ' . $fbrgy . ', ' . $fcity . ', ' . $fprovince . ', ' . $fzip;
        } else {
            try {
                $sqlGetFatherAddress = "SELECT faddress FROM students WHERE studID = :studID";
                $stmtFatherAddress = $conn->prepare($sqlGetFatherAddress);
                $stmtFatherAddress->bindParam(':studID', $studID);
                $stmtFatherAddress->execute();
                $resultFatherAddress = $stmtFatherAddress->fetch(PDO::FETCH_ASSOC);
                $faddress = $resultFatherAddress['faddress'] ?? '';
            } catch (PDOException $e) {
                echo 'Database error (Father Address Fetch): ' . $e->getMessage();
                exit();
            }
        }
    }

    // Prepare the SQL query for updating father's information
    $sqlFather = "UPDATE students SET
            flname = :flname,
            ffname = :ffname,
            fmname = :fmname,
            fage = :fage,
            foccu = :foccu,
            feduc = :feduc,
            fcontact = :fcontact,
            foffice = :foffice,
            femail = :femail,
            faddress = :faddress,
            fdob = :fdob
        WHERE studID = :studID";

    try {
        $stmtFather = $conn->prepare($sqlFather);

        // Bind parameters
        $stmtFather->bindParam(':flname', $flname);
        $stmtFather->bindParam(':ffname', $ffname);
        $stmtFather->bindParam(':fmname', $fmname);
        $stmtFather->bindParam(':fage', $fage);
        $stmtFather->bindParam(':foccu', $foccu);
        $stmtFather->bindParam(':feduc', $feduc);
        $stmtFather->bindParam(':fcontact', $fcontact);
        $stmtFather->bindParam(':foffice', $foffice);
        $stmtFather->bindParam(':femail', $femail);
        $stmtFather->bindParam(':faddress', $faddress);
        $stmtFather->bindParam(':fdob', $fdob);
        $stmtFather->bindParam(':studID', $studID);

        $stmtFather->execute();

    } catch (PDOException $e) {
        echo 'Database error (Father Update): ' . $e->getMessage();
        exit();
    }

    $mlname = $_POST['txtMotherlname'] ?? null;
    $mfname = $_POST['txtMotherfname'] ?? null;
    $mmname = $_POST['txtMothermname'] ?? null;
    $mage = $_POST['txtMotherAge'] ?? null;
    $moccu = $_POST['txtMotherOccupation'] ?? null;
    $meduc = $_POST['txtMotherEducAtt'] ?? null;
    $mcontact = $_POST['txtMotherContactNum'] ?? null;
    $moffice = $_POST['txtMotherOffice'] ?? null;
    $memail = $_POST['txtMotherEmail'] ?? null;
    $mdob = $_POST['txtMotherDOB'] ?? null;

    // Check if 'motherNoRecord' checkbox is checked
    $noMotherRecord = isset($_POST['motherNoRecord']) ? $_POST['motherNoRecord'] : '';

    if ($noMotherRecord === 'on') {
        $mlname = $mfname = $mmname = $mage = $dob = $moccu = $meduc = $mcontact = $moffice = $memail = NULL;
        $maddress = NULL; // Ensure mother's address is also set to NULL
    } else {
        // Handle mother's address
        $addressMotherCB = isset($_POST['keepMotherAddressCB']) ? $_POST['keepMotherAddressCB'] : '';
        if ($addressMotherCB !== 'on') {
            $mhouse = $_POST['txtmHouseNumber'] ?? '';
            $mstreet = $_POST['txtmStreetName'] ?? '';
            $msubd = $_POST['txtmSubdivision'] ?? '';
            $mregion = $_POST['txtmRegion'] ?? '';
            $mprovince = $_POST['txtmProvince'] ?? '';
            $mcity = $_POST['txtmCity'] ?? '';
            $mbrgy = $_POST['txtmBarangay'] ?? '';
            $mzip = $_POST['txtmPostalCode'] ?? '';

            $maddress = $mhouse . ' ' . $mstreet . ', ' . $msubd . ', ' . $mbrgy . ', ' . $mcity . ', ' . $mprovince . ', ' . $mzip;
        } else {
            try {
                $sqlGetMotherAddress = "SELECT maddress FROM students WHERE studID = :studID";
                $stmtMotherAddress = $conn->prepare($sqlGetMotherAddress);
                $stmtMotherAddress->bindParam(':studID', $studID);
                $stmtMotherAddress->execute();
                $resultMotherAddress = $stmtMotherAddress->fetch(PDO::FETCH_ASSOC);
                $maddress = $resultMotherAddress['maddress'] ?? '';
            } catch (PDOException $e) {
                echo 'Database error (Mother Address Fetch): ' . $e->getMessage();
                exit();
            }
        }
    }

    // Prepare the SQL query for updating mother's information
    $sqlMother = "UPDATE students SET
            mlname = :mlname,
            mfname = :mfname,
            mmname = :mmname,
            mage = :mage,
            moccu = :moccu,
            meduc = :meduc,
            mcontact = :mcontact,
            moffice = :moffice,
            memail = :memail,
            maddress = :maddress,
            mdob = :mdob
        WHERE studID = :studID";

    try {
        $stmtMother = $conn->prepare($sqlMother);

        // Bind parameters
        $stmtMother->bindParam(':mlname', $mlname);
        $stmtMother->bindParam(':mfname', $mfname);
        $stmtMother->bindParam(':mmname', $mmname);
        $stmtMother->bindParam(':mage', $mage);
        $stmtMother->bindParam(':moccu', $moccu);
        $stmtMother->bindParam(':meduc', $meduc);
        $stmtMother->bindParam(':mcontact', $mcontact);
        $stmtMother->bindParam(':moffice', $moffice);
        $stmtMother->bindParam(':memail', $memail);
        $stmtMother->bindParam(':maddress', $maddress);
        $stmtMother->bindParam(':mdob', $mdob);
        $stmtMother->bindParam(':studID', $studID);

        $stmtMother->execute();

    }catch (PDOException $e) {
        echo 'Database error (Mother Address Fetch): ' . $e->getMessage();
        exit();
    }


    // for guardian query
    $relationship = $_POST['selGuardian'] ?? '';

    switch ($relationship) {
        case 'Father':
            $gcontact = $fcontact;
            $glname = $flname;
            $gfname = $ffname;
            $gmname = $fmname;
            $gaddress = $faddress;
            break;
    
        case 'Mother':
            $gcontact = $mcontact;
            $glname = $mlname;
            $gfname = $mfname;
            $gmname = $mmname;
            $gaddress = $maddress;
            break;

        case 'others':
            $gcontact = $_POST['txtGdContactNum'] ?? '';
            $glname = $_POST['txtGdlname'] ?? '';
            $gfname = $_POST['txtGdfname'] ?? '';
            $gmname = $_POST['txtGdmname'] ?? '';
            $gaddress = $address;
            break;
    
        default:
            $gcontact = '';
            $glname = '';
            $gfname = '';
            $gmname = '';
            $gaddress = '';
            break;
    }
                $sqlGuardian = "UPDATE students SET
                relationship = :relationship,
                glname = :glname,
                gfname = :gfname,
                gmname = :gmname,
                gcontact = :gcontact,
                gaddress = :gaddress
            WHERE studID = :studID";

            try {
            $stmtGuardian = $conn->prepare($sqlGuardian);

            // Bind parameters
            $stmtGuardian->bindParam(':relationship', $relationship);
            $stmtGuardian->bindParam(':glname', $glname);
            $stmtGuardian->bindParam(':gfname', $gfname);
            $stmtGuardian->bindParam(':gmname', $gmname);
            $stmtGuardian->bindParam(':gcontact', $gcontact);
            $stmtGuardian->bindParam(':gaddress', $gaddress);
            $stmtGuardian->bindParam(':studID', $studID);

            $stmtGuardian->execute();

        }catch (PDOException $e) {
            echo 'Database error (Guardian Address Fetch): ' . $e->getMessage();
            exit();
        }

    if ($stmtGuardian->execute()) {
        // Redirect after both updates are successful
        header('Location: manage_studentsRec.php?alert=student_update');
        exit();
    } else {
        echo 'Error updating father\'s record';
    }
}
?>
