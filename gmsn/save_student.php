<?php
require_once "includes/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $lrn = $_POST['txtStudentLRN'];
    $lname = ucwords(strtolower($_POST['txtStudentlname']));
    $fname = ucwords(strtolower($_POST['txtStudentfname']));
    $mname = ucwords(strtolower($_POST['txtStudentmname']));
    $gender = ucwords(strtolower($_POST['txtStudentGender']));
    $dob = $_POST['dtDateOfBirth'];
    $age = $_POST['txtAge'];
    $pob = ucwords(strtolower($_POST['txtPlaceOfBirth']));
    $contact = $_POST['txtContactNum'] ?? NULL;

    
    // Handle 'other' options for nationality, language, and religion
    $nationality = isset($_POST['selNationality']) && $_POST['selNationality'] === 'other'
        ? ucwords(strtolower($_POST['txtActualNationality']))
        : ucwords(strtolower($_POST['selNationality']));

    $language = isset($_POST['selLanguage']) && $_POST['selLanguage'] === 'other'
        ? ucwords(strtolower($_POST['txtActualLanguage']))
        : ucwords(strtolower($_POST['selLanguage']));

    $religion = isset($_POST['selReligion']) && $_POST['selReligion'] === 'other'
        ? ucwords(strtolower($_POST['txtActualReligion']))
        : ucwords(strtolower($_POST['selReligion']));
        


    // Handle photo upload
    $photo = $_FILES['txtPhoto'] ?? NULL;
    $photoFilename = '';

    if ($photo['error'] == UPLOAD_ERR_OK) {
        // Define the upload directory
        $uploadDir = 'uploads/photo/';
        $photoFilename = basename($photo['name']);
        $photoPath = $uploadDir . $photoFilename;

        // Move the uploaded file to the upload directory
        if (!move_uploaded_file($photo['tmp_name'], $photoPath)) {
            die('File upload error');
        }
    }

    $houseNumber = ucwords(strtolower($_POST['txtHouseNumber'] ?? ''));
    $streetName = ucwords(strtolower($_POST['txtStreetName'] ?? ''));
    $subdivision = ucwords(strtolower($_POST['txtSubdivision'] ?? ''));
    $region = ucwords(strtolower($_POST['txtRegion'] ?? ''));
    $province = ucwords(strtolower($_POST['txtProvince'] ?? ''));
    $city = ucwords(strtolower($_POST['txtCity'] ?? ''));
    $barangay = ucwords(strtolower($_POST['txtBarangay'] ?? ''));
    $postalCode = $_POST['txtPostalCode'] ?? '';

    $address = $houseNumber . ' ' . $streetName . ', ' . $subdivision . ', ' . $barangay . ', ' . $city . ', ' . $province . ', ' . $postalCode;

    // Retrieve father's information
    $flname = ucwords(strtolower($_POST['txtFatherlname'] ?? ''));
    $ffname = ucwords(strtolower($_POST['txtFatherfname'] ?? ''));
    $fmname = ucwords(strtolower($_POST['txtFathermname'] ?? ''));
    $fage = $_POST['txtFatherAge'] ?? '';
    $foccu = ucwords(strtolower($_POST['txtFatherOccupation'] ?? ''));
    $feduc = ucwords(strtolower($_POST['txtFatherEducAtt'] ?? ''));
    $fcontact = $_POST['txtFatherContactNum'] ?? '';
    $foffice = ucwords(strtolower($_POST['txtFatherOffice'] ?? ''));
    $femail = $_POST['txtFatherEmail'] ?? '';
    $fdob = $_POST['txtFatherYear'] ?? '';

    $fatherNoRecord = isset($_POST['fatherNoRecord']) ? $_POST['fatherNoRecord'] : '';


    if ($fatherNoRecord !== 'on') {
        $sameAddressCheckbox = isset($_POST['sameAddressCheckbox']) ? $_POST['sameAddressCheckbox'] : '';

        if ($sameAddressCheckbox !== 'on') {
            $fhouseNumber = ucwords(strtolower($_POST['txtfHouseNumber'] ?? ''));
            $fstreetName = ucwords(strtolower($_POST['txtfStreetName'] ?? ''));
            $fsubdivision = ucwords(strtolower($_POST['txtfSubdivision'] ?? ''));
            $fregion = ucwords(strtolower($_POST['txtfRegion'] ?? ''));
            $fprovince = ucwords(strtolower($_POST['txtfProvince'] ?? ''));
            $fcity = ucwords(strtolower($_POST['txtfCity'] ?? ''));
            $fbarangay = ucwords(strtolower($_POST['txtfBarangay'] ?? ''));
            $fpostalCode = $_POST['txtfPostalCode'] ?? '';
    
            $faddress = $fhouseNumber . ' ' . $fstreetName . ', ' . $fsubdivision . ', ' . $fbarangay . ', ' . $fcity . ', ' . $fprovince . ', ' . $fpostalCode;
        }else {
            $faddress = $address;
        }
    }else{
        $flname = $ffname = $fmname = $fage = $foccu = $feduc = $fcontact = $foffice = $femail = $fdob = $faddress = NULL;
    }


    // Retrieve mother's information
    $mlname = ucwords(strtolower($_POST['txtMotherlname'] ?? ''));
    $mfname = ucwords(strtolower($_POST['txtMotherfname'] ?? ''));
    $mmname = ucwords(strtolower($_POST['txtMothermname'] ?? ''));
    $mage = $_POST['txtMotherAge'] ?? '';
    $moccu = ucwords(strtolower($_POST['txtMotherOccupation'] ?? ''));
    $meduc = ucwords(strtolower($_POST['txtMotherEducAtt'] ?? ''));
    $mcontact = $_POST['txtMotherContactNum'] ?? '';
    $moffice = ucwords(strtolower($_POST['txtMotherOffice'] ?? ''));
    $memail = $_POST['txtMotherEmail'] ?? '';
    $mdob = $_POST['txtMotherYear'] ?? '';

    $motherNoRecord = isset($_POST['motherNoRecord']) ? $_POST['motherNoRecord'] : '';


    if ($motherNoRecord !== 'on') {
        $sameAddressCheckboxM = isset($_POST['sameAddressCheckboxM']) ? $_POST['sameAddressCheckboxM'] : '';
        if ($sameAddressCheckboxM !== 'on') {
            $mhouseNumber = ucwords(strtolower($_POST['txtmHouseNumber'] ?? ''));
            $mstreetName = ucwords(strtolower($_POST['txtmStreetName'] ?? ''));
            $msubdivision = ucwords(strtolower($_POST['txtmSubdivision'] ?? ''));
            $mregion = ucwords(strtolower($_POST['txtmRegion'] ?? ''));
            $mprovince = ucwords(strtolower($_POST['txtmProvince'] ?? ''));
            $mcity = ucwords(strtolower($_POST['txtmCity'] ?? ''));
            $mbarangay = ucwords(strtolower($_POST['txtmBarangay'] ?? ''));
            $mpostalCode = $_POST['txtmPostalCode'] ?? '';
    
            $maddress = $mhouseNumber . ' ' . $mstreetName . ', ' . $msubdivision . ', ' . $mbarangay . ', ' . $mcity . ', ' . $mprovince . ', ' . $mpostalCode;
        }else {
            $maddress = $address;
        }

    }else{
        $mlname = $mfname = $mmname = $mage = $moccu = $meduc = $mcontact = $moffice = $memail = $mdob = $maddress = NULL;
    }

    $sameAddressCheckboxM = isset($_POST['sameAddressCheckboxM']) ? $_POST['sameAddressCheckboxM'] : '';


    // for guardian query
    $guardian = $_POST['selGuardian'] ?? '';

    switch ($guardian) {
        case 'Father':
            $gcontact = $fcontact;
            $glname = $flname;
            $gfname = $ffname;
            $gmname = $fmname;
            $gaddress = $faddress;
            $relationship = $guardian;
            break;
    
        case 'Mother':
            $gcontact = $mcontact;
            $glname = $mlname;
            $gfname = $mfname;
            $gmname = $mmname;
            $gaddress = $maddress;
            $relationship = $guardian;
            break;

        case 'Others':
            $gcontact = $_POST['txtGdContactNum'] ?? '';
            $glname = $_POST['txtGdlname'] ?? '';
            $gfname = $_POST['txtGdfname'] ?? '';
            $gmname = $_POST['txtGdmname'] ?? '';
            $relationship = $_POST['otherGuardian'] ?? '';
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

   
    // Check if LRN already exists
    $checkSql = "SELECT COUNT(*) FROM students WHERE lrn = :lrn";
    try {
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bindParam(':lrn', $lrn);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            // LRN already exists
            header("Location: manage_studentsRec.php?alert=lrn_exists");
            exit();
        }

        // Prepare the SQL query for inserting student information
        $sql = "INSERT INTO students (
                lrn, lname, fname, mname, gender, dob, age, pob, contact, address, nationality, language, religion, photo,
                flname, ffname, fmname, fage, foccu, feduc, fcontact, foffice, femail, faddress, fdob,
                mlname, mfname, mmname, mage, moccu, meduc, mcontact, moffice, memail, maddress, mdob,
                glname, gfname, gmname, gcontact, relationship, gaddress
            ) VALUES (
                :lrn, :lname, :fname, :mname, :gender, :dob, :age, :pob, :contact, :address, :nationality, :language, :religion, :photo,
                :flname, :ffname, :fmname, :fage, :foccu, :feduc, :fcontact, :foffice, :femail, :faddress, :fdob,
                :mlname, :mfname, :mmname, :mage, :moccu, :meduc, :mcontact, :moffice, :memail, :maddress, :mdob,
                :glname, :gfname, :gmname, :gcontact, :relationship, :gaddress
            )";

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

            // Bind father's information parameters
            $stmt->bindParam(':flname', $flname);
            $stmt->bindParam(':ffname', $ffname);
            $stmt->bindParam(':fmname', $fmname);
            $stmt->bindParam(':fage', $fage);
            $stmt->bindParam(':foccu', $foccu);
            $stmt->bindParam(':feduc', $feduc);
            $stmt->bindParam(':fcontact', $fcontact);
            $stmt->bindParam(':foffice', $foffice);
            $stmt->bindParam(':femail', $femail);
            $stmt->bindParam(':faddress', $faddress);
            $stmt->bindParam(':fdob', $fdob);

            // Bind mother's information parameters
            $stmt->bindParam(':mlname', $mlname);
            $stmt->bindParam(':mfname', $mfname);
            $stmt->bindParam(':mmname', $mmname);
            $stmt->bindParam(':mage', $mage);
            $stmt->bindParam(':moccu', $moccu);
            $stmt->bindParam(':meduc', $meduc);
            $stmt->bindParam(':mcontact', $mcontact);
            $stmt->bindParam(':moffice', $moffice);
            $stmt->bindParam(':memail', $memail);
            $stmt->bindParam(':maddress', $maddress);
            $stmt->bindParam(':mdob', $mdob);
            
            // Bind guardian's information parameters
            $stmt->bindParam(':glname', $glname);
            $stmt->bindParam(':gfname', $gfname);
            $stmt->bindParam(':gmname', $gmname);
            $stmt->bindParam(':gcontact', $gcontact);
            $stmt->bindParam(':relationship', $relationship);
            $stmt->bindParam(':gaddress', $gaddress);
            $stmt->execute();

            // Redirect after the insert is successful
            header('Location: manage_studentsRec.php?alert=student_added');
            exit();
        } catch (PDOException $e) {
            echo 'Database error (Student Insert): ' . $e->getMessage();
            exit();
        }
    } catch (PDOException $e) {
        echo 'Database error (LRN Check): ' . $e->getMessage();
        exit();
    }
}
?>
