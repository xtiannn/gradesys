<?php
include 'session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Student's Information</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/gmsnlogo.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .form-control {
            border: none;
            border-radius: 0;
            border-bottom: 1px solid #ced4da; 
            box-shadow: none;
            
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: bold;
        }
        .header p {
            margin-top: 10px;
            font-size: 18px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        fieldset {
            border: 2px solid #1a237e; /* Navy Blue Border */
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
        }
        legend {
            font-size: 24px;
            font-weight: bold;
            color: #1a237e; /* Navy Blue Text */
            border-bottom: 2px solid #1a237e; /* Navy Blue Bottom Border */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .form-control:focus {
            border-color: #ced4da;
            box-shadow: none;
        }
        .required-asterisk {
        color: red;
        font-size: 20px;
        margin: 5px;
    }
    .hidden {
    display: none;
    }
    .no-readonly-color {
    background-color: #fff !important; /* Change to your desired background color */
    color: #000; /* Ensure text color is visible */
    border: 1px solid #ced4da; /* Ensure border looks the same */
    cursor: text; /* Change cursor to text */
}
#studentAddress {
    cursor: default; 
    font-size: 17px;
    width: 100%; 
    border: none; 
    background-color: white; 
    margin-bottom: 10px;
    margin-left: 20px;
    padding: 0;
    box-shadow: none; 
    outline: none; 
    color: #212529; 
    resize: none; 
}
#fatherAddress {
    cursor: default; 
    font-size: 17px;
    width: 100%; 
    border: none; 
    background-color: white; 
    margin-bottom: 10px;
    margin-left: 20px;
    padding: 0;
    box-shadow: none; 
    outline: none; 
    color: #212529; 
    resize: none; 
}
#motherAddress {
    cursor: default; 
    font-size: 17px;
    width: 100%; 
    border: none; 
    background-color: white; 
    margin-bottom: 10px;
    margin-left: 20px;
    padding: 0;
    box-shadow: none; 
    outline: none; 
    color: #212529; 
    resize: none; 
}


.sm{
    font-size: 20px;
    border-bottom: 0px solid #1a237e; /* Navy Blue Bottom Border */
}
.custom-container {
      margin-left: -30px;
      margin-right: -15px;
    }
    .container {
      width: 100%; 
    }
    .custom-card-title {
    padding: 1px 0;
    margin: 2px 2px;
    font-size: 20px;
    font-weight: 600;
    color: #012970;
    font-family: "Poppins", sans-serif;
    }

    .custom-card-title span {
    color: #012970;
    font-size: 15px;
    font-weight: 400;
    margin-left: 8px
    }

</style>
</head>

<body>

    <?php require_once "support/header.php"?>
    <?php require_once "support/sidebar.php"?>
    <?php
        require_once('includes/config.php');

        if (isset($_GET['studID'])) {
            $studID = $_GET['studID'];

            $stmt = $conn->prepare("SELECT * FROM students WHERE studID = :studID");
            $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            $isFatherInfoMissing =  is_null($student['flname']) || is_null($student['ffname']) || 
                                    is_null($student['fmname']) || is_null($student['fage']) || 
                                    is_null($student['fcontact']) || is_null($student['feduc']) ||
                                    is_null($student['faddress']);

            $isMotherInfoMissing =  is_null($student['mlname']) || is_null($student['mfname']) || 
                                    is_null($student['mmname']) || is_null($student['mage']) || 
                                    is_null($student['mcontact']) || is_null($student['meduc']) ||
                                    is_null($student['maddress']);
           
        
                                    
        } 
    ?>

    <main id="main" class="main mt-0">
        <section class="section">
            <div class="custom-container">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        <form id="studentFormUpdate" action="update_student.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <input type="hidden" name="studID" value="<?php echo ($student['studID']); ?>">
                        <fieldset class="border p-4 rounded mb-4" id="studentField">
                            <legend>Student Information</legend>
                            <div class="row mb-4">
                                <!-- Photo Input -->
                                <div class="col-md-6 mb-3 mt-3">
                                    <label for="photo" class="form-label fw-bold">Select a Photo:</label>
                                    <div class="input-group" style="border: 1px solid #ccc; border-radius: 5px; overflow: hidden;">
                                        <input type="file" id="photo" name="txtPhoto" class="form-control" accept="image/*" onchange="previewImage(event)" style="border: none;">
                                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('photo').click()">Browse</button>
                                    </div>
                                </div>
                                <!-- Image Preview -->
                                <div class="col-md-5 mb-3 d-flex justify-content-end position-relative">
                                    <div class="mt-2">
                                        <?php
                                        // Check if photo filename exists and set default image if not
                                        $photoPath = 'uploads/photo/';
                                        $photoFilename = $student['photo'] ?? ''; // Fetch photo filename from database
                                        $photoUrl = $photoFilename ? $photoPath . $photoFilename : 'assets/img/user.png';
                                        ?>
                                        <img id="imagePreview" src="<?php echo htmlspecialchars($photoUrl); ?>" alt="Preview" class="rounded" style="position: absolute; top: 0; right: 0; width: 200px; height: 180px; border: 1px solid #ccc; padding: 0; margin: 0;">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <!-- LRN Input -->
                                <div class="col-md-8 mb-3">
                                    <label for="lrn" class="form-label fw-bold">Learner Reference Number: <span class="required-asterisk">*</span></label>
                                    <input type="number" class="form-control" pattern="\d{12}" min="100000000000" max="999999999999" id="lrn" name="txtStudentLRN" placeholder="Student's LRN" value="<?php echo $student['lrn']; ?>" required>
                                    <div class="invalid-feedback">
                                        Please enter a valid 12-digit LRN.
                                    </div>
                                </div>
                            </div>
                            <!-- Name Inputs -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                <label for="txtStudentlname" class="form-label fw-bold">Last Name: <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="studentlname" name="txtStudentlname" placeholder="Last Name"  value="<?php echo ($student['lname']); ?>" required>
                                    <div class="invalid-feedback">
                                    Please enter Last Name.
                                    </div>
                                </div>
                                <div class="col-md-4">
                                <label for="studentfname" class="form-label fw-bold">First Name: <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" id="studentfname" name="txtStudentfname" placeholder="First Name"  value="<?php echo ($student['fname']); ?>" required>
                                    <div class="invalid-feedback">
                                        Please enter First Name.
                                    </div>
                                </div>
                                <div class="col-md-4">
                                <label for="studentmname" class="form-label fw-bold">Middle Name: </label>
                                    <input type="text" class="form-control" id="studentmname" name="txtStudentmname" placeholder="Middle Name" value="<?php echo ($student['mname']); ?>">
                                </div>
                            </div>
                            <!-- Gender and Contact Number Inputs -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="gender" class="form-label fw-bold">Gender: <span class="required-asterisk">*</span></label>
                                    <select class="form-select form-control" id="gender" name="txtStudentGender" required>
                                        <option value="" disabled>Select Gender</option>
                                        <option value="Male" <?php if ($student['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                        <option value="Female" <?php if ($student['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a gender.
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="dateofbirth" class="form-label fw-bold">Date of Birth: <span class="required-asterisk">*</span></label>
                                    <input type="date" class="form-control" name="dtDateOfBirth" id="dateofbirth" value="<?php echo ($student['dob']); ?>" required>
                                    <div class="invalid-feedback">
                                        Please enter valid Date of Birth.
                                    </div>
                                </div>
                                <script>
                                    // Get today's date in the format YYYY-MM-DD
                                    let today = new Date().toISOString().split('T')[0];

                                    // Get the date input element
                                    let dateOfBirthInput = document.getElementById('dateofbirth');

                                    // Set the max attribute to today to prevent future dates
                                    dateOfBirthInput.setAttribute('max', today);

                                    // On input validation
                                    dateOfBirthInput.oninput = function() {
                                        // Check if the selected date is greater than today's date
                                        if (dateOfBirthInput.value > today) {
                                            dateOfBirthInput.setCustomValidity("Date of Birth cannot be today or a future date.");
                                            dateOfBirthInput.classList.add('is-invalid'); // Add invalid class to show feedback
                                        } else {
                                            dateOfBirthInput.setCustomValidity(""); // Clear the error message
                                            dateOfBirthInput.classList.remove('is-invalid'); // Remove invalid class
                                        }
                                    };
                                </script>
                                <div class="col-md-4">
                                    <label for="age" class="form-label fw-bold">Age: <span class="required-asterisk">*</span></label>
                                    <input type="number" name="txtAge" id="age" class="form-control no-readonly-color" value="<?php echo $student['age'] ?? ''; ?>" readonly >
                                    <div class="invalid-feedback">
                                        Please enter age.
                                    </div>
                                </div>
                            </div>
                            <!-- Date of Birth and Place of Birth Inputs -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="placeofbirth" class="form-label fw-bold">Place of Birth: <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" name="txtPlaceOfBirth" id="placeofbirth" value="<?php echo ($student['pob']); ?>" required>
                                    <div class="invalid-feedback">
                                        Please enter place of birth.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="contactnum" class="form-label fw-bold">Contact Number: </label>
                                    <div class="input-group">
                                        <div class="input-group-text">(+63)</div>
                                            <input type="number" 
                                            class="form-control" 
                                            name="txtContactNum" 
                                            id="contactnum" 
                                            pattern="\d{10}" 
                                            placeholder="Enter the last 10-digits"  
                                            value="<?php echo $student['contact'] ?? ''; ?>" 
                                            >
                                    </div>
                                    <div class="invalid-feedback">
                                        Please enter the an valid contact number.
                                    </div>
                                </div>
                            </div>
                            <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                    const contactInput = document.getElementById('contactnum');

                                    contactInput.addEventListener('input', function () {
                                        let value = contactInput.value.replace(/\D/g, '');
                                        
                                        if (value.length > 10) {
                                            value = value.slice(0, 10);
                                        }
                                        
                                        contactInput.value = value;
                                    });
                                });
                                </script>
                            <!-- Address for student -->
                            <div class="row mb-4">
                                <!-- Parent Label for Address of student -->
                                <input type="checkbox" name="keepAddressCB" id="keepAddressCB" style="display: none;" checked>
                                <div class="col-12 d-flex align-items-center justify-content-between">
                                    <label for="studentAddress" class="form-label fw-bold me-2">Address: </label>
                                    <input type="text" id="studentAddress" name="studentAddress" value="<?php echo !empty($student['address']) ? htmlspecialchars($student['address']) : '(Address Not yet defined.)'; ?>" readonly>
                                    <div class="d-flex mb-2">
                                        <a href="#" id="editAddress" class="btn btn-outline-primary btn-sm me-2">
                                            <i class="fas fa-edit"></i> Update Address
                                        </a>
                                        <a href="#" id="cancelEditAddress" class="btn btn-danger btn-sm" style="display: none;">
                                            <i class="fas fa-times"></i> Cancel Updating
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- only show when updating address -->
                            <div id="address" style="display: none;"> 
                                <div class="row mb-4">
                                    <!-- House Number, Street Name, Barangay, Subdivision/Village -->
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" id="houseNumber" name="txtHouseNumber" placeholder="House No." >
                                        <div class="invalid-feedback">
                                            Please enter the house number.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" id="streetName" name="txtStreetName" placeholder="Street Name" required>
                                        <div class="invalid-feedback">
                                            Please enter the street name.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" id="subdivision" name="txtSubdivision" placeholder="Subdivision/Village" required>
                                        <div class="invalid-feedback">
                                            Please enter the subdivision.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                    <select class="form-select form-control selectpicker" id="region" data-live-search="true" data-dropup-auto="false" required>
                                        <option value="" selected disabled>Select Region</option>
                                        <?php
                                        try {
                                            $query = "SELECT regCode, regDesc FROM refregion";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['regCode'] . '">' . $row['regDesc'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching Regions</option>';
                                        }
                                        ?>
                                    </select>
                                        <input type="hidden" id="selectedRegionDesc" name="txtRegion" value="">
                                        <div class="invalid-feedback">
                                            Please select a province.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                    <select class="form-select form-control selectpicker" id="province" data-live-search="true" data-dropup-auto="false" required>
                                        <option value="" selected disabled>Select Province</option>
                                    </select>
                                    <input type="hidden" id="selectedProvinceDesc" name="txtProvince" value="">
                                        <div class="invalid-feedback">
                                            Please select a province.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                    <select class="form-select form-control selectpicker" id="city" data-live-search="true" required>
                                        <option value="" selected disabled>Select City/Municipality</option>
                                    </select>
                                    <input type="hidden" id="selectedCityDesc" name="txtCity" value="">
                                        <div class="invalid-feedback">
                                            Please select city or municipality.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <select class="form-select form-control selectpicker" id="barangay" data-live-search="true" data-dropup-auto="false" required>
                                            <option value="" selected disabled>Select Barangay</option>
                                        </select>
                                        <input type="hidden" id="selectedBarangayDesc" name="txtBarangay"  value="">
                                        <div class="invalid-feedback">
                                            Please select barangay.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" id="postalCode" name="txtPostalCode" placeholder="Postal Code" required>
                                        <div class="invalid-feedback">
                                            Please enter the Postal code.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- nationality Inputs -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="nationality" class="form-label fw-bold">Nationality: <span class="required-asterisk">*</span></label>
                                    <input type="text" class="form-control" name="selNationality" id="nationality" placeholder="Enter nationality" value="Filipino" value="<?php echo ($student['nationality']); ?>">
                                    <div class="invalid-feedback">
                                        Please enter nationality.
                                    </div>
                                </div>
                                <!-- Language Input -->
                                <div class="col-md-4">
                                    <label for="language" class="form-label fw-bold">Language/s Spoken: <span class="required-asterisk">*</span></label>
                                    <select name="selLanguage" id="language" class="form-select form-control" >
                                        <option value="Tagalog" <?php echo ($student['language'] == 'Tagalog') ? 'selected' : '';?>>Tagalog</option>
                                        <option value="English" <?php echo ($student['language'] == 'English') ? 'selected' : '';?>>English</option>
                                        <option value="other">---Others---</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="religion" class="form-label fw-bold">Religion: <span class="required-asterisk">*</span></label>
                                    <select name="selReligion" id="religion" class="form-select form-control" onchange="toggleOtherReligion(this)">
                                        <option disabled>Select Religion</option>
                                        <option value="Roman Catholic" <?php echo ($student['religion'] == 'Roman Catholic') ? 'selected' : ''; ?>>Roman Catholic</option>
                                        <option value="Protestant" <?php echo ($student['religion'] == 'Protestant') ? 'selected' : ''; ?>>Protestant</option>
                                        <option value="Islam" <?php echo ($student['religion'] == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                                        <option value="Buddhism" <?php echo ($student['religion'] == 'Buddhism') ? 'selected' : ''; ?>>Buddhism</option>
                                        <option value="Hinduism" <?php echo ($student['religion'] == 'Hinduism') ? 'selected' : ''; ?>>Hinduism</option>
                                        <option value="Judaism" <?php echo ($student['religion'] == 'Judaism') ? 'selected' : ''; ?>>Judaism</option>
                                        <option value="Sikhism" <?php echo ($student['religion'] == 'Sikhism') ? 'selected' : ''; ?>>Sikhism</option>
                                        <option value="Jainism" <?php echo ($student['religion'] == 'Jainism') ? 'selected' : ''; ?>>Jainism</option>
                                        <option value="Bahá'í" <?php echo ($student['religion'] == 'Bahá\'í') ? 'selected' : ''; ?>>Bahá'í</option>
                                        <option value="Zoroastrianism" <?php echo ($student['religion'] == 'Zoroastrianism') ? 'selected' : ''; ?>>Zoroastrianism</option>
                                        <option value="Shinto" <?php echo ($student['religion'] == 'Shinto') ? 'selected' : ''; ?>>Shinto</option>
                                        <option value="Taoism" <?php echo ($student['religion'] == 'Taoism') ? 'selected' : ''; ?>>Taoism</option>
                                    </select>

                                    <div id="otherReligionContainer" style="display: none; margin-top: 10px;">
                                        <label for="otherReligion" class="form-label fw-bold">Specify Other Religion:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="otherReligion" name="txtOtherReligion" placeholder="Enter Other Religion" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- Father's Information -->
                        <fieldset class="border p-4 rounded mb-4" id="fatherField">
                            <legend>Father's Information</legend>     
                            <legend class="sm">
                                <input type="checkbox" name="fatherNoRecord" id="fatherNoRecord">
                                <small><label for="fatherNoRecord">Mark if the student doesn't have a father's record.</label></small>
                            </legend>
        
                            <div class="fatherInfo" id="fatherInfo">
                                <!-- Father Inputs -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                    <label for="fatherlname" class="form-label fw-bold">Last Name: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="fatherlname" name="txtFatherlname" placeholder="Father's Last Name" value="<?php echo $student['flname'] ?? '' ?>" required>
                                        <div class="invalid-feedback">
                                            Please enter the father's Last Name.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <label for="fatherfname" class="form-label fw-bold">First Name: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="fatherfname" name="txtFatherfname" placeholder="Father's First Name" value="<?php echo $student['ffname']?>" required>
                                        <div class="invalid-feedback">
                                            Please enter the father's First Name.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <label for="fathermname" class="form-label fw-bold">Middle Name: </label>
                                        <input type="text" class="form-control" id="fathermname" name="txtFathermname" placeholder="Father's Middle Name" value="<?php echo $student['fmname']?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <?php
                                            // Fetch the birth year from the database
                                            $fatherbirthYear = $student['fdob'] ?? ''; 

                                            // Calculate the age based on the current year
                                            $currentYear = date('Y');
                                            if ($fatherbirthYear) {
                                                $fatherage = $currentYear - $fatherbirthYear;
                                            } else {
                                                $fatherage = '';
                                            }
                                        ?>
                                    <label for="fatherage" class="form-label fw-bold">Age:<?php echo $fatherbirthYear?> <span class="required-asterisk">*</span></label>
                                        <input type="number" id="fatherage" name="txtFatherAge" class="form-control" placeholder="Father's Age" value="<?php echo $fatherage ?>" required>
                                        <input type="hidden" id="fatherBirthYear" name="txtFatherDOB" value="<?php echo $fatherbirthYear ?>">
                                        <div class="invalid-feedback">
                                            Please enter the father's Age.
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            function updateAgeAndDOBFather() {
                                                var fatherageInput = document.getElementById('fatherage');
                                                var fatherBirthYearInput = document.getElementById('fatherBirthYear');

                                                // Get the current year
                                                var fathercurrentYear = new Date().getFullYear();

                                                // Calculate the birth year based on the age input
                                                var fatherage = parseInt(fatherageInput.value, 10);
                                                if (!isNaN(fatherage) && fatherage >= 0) {
                                                    var fathercalculatedBirthYear = fathercurrentYear - fatherage;
                                                    fatherBirthYearInput.value = fathercalculatedBirthYear;
                                                } else {
                                                    fatherBirthYearInput.value = ''; 
                                                }
                                            }

                                            // Add event listeners for updates
                                            document.getElementById('fatherage').addEventListener('input', updateAgeAndDOBFather);

                                            // Initial update on page load if age or birth year is prefilled
                                            updateAgeAndDOBFather();
                                        });
                                    </script>
                                    <div class="col-md-4">
                                        <label for="fathercontactnumber" class="form-label fw-bold">Contact Number: <span class="required-asterisk">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">(+63)</span>
                                            <input type="text" 
                                            class="form-control" 
                                            id="fathercontactnumber" 
                                            name="txtFatherContactNum" 
                                            pattern="\d{10}" 
                                            placeholder="Enter the last 10-digits" 
                                            value="<?php echo $student['fcontact'] ?? ''?>"
                                            required>
                                            <div class="invalid-feedback">
                                                Please enter a valid father's contact number.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="fathereducatt" class="form-label fw-bold">Educational Attainment: <span class="required-asterisk">*</span></label>
                                        <?php $selectedFeduc = isset($student['feduc']) ? $student['feduc'] : '';?>
                                        <select class="form-select form-control" name="txtFatherEducAtt" id="fathereducatt" required>
                                        <option disabled value="" <?php echo empty($selectedFeduc) ? 'selected' : ''?>>Select Education</option>
                                        <option value="Elementary Level" <?php $selectedFeduc === 'Elementary Level' ? 'selected' : ''?>>Elementary Level</option>
                                        <option value="Elementary Graduate" <?php $selectedFeduc === 'Elementary Graduate' ? 'selected' : ''?>>Elementary Graduate</option>
                                        <option value="High School Level" <?php $selectedFeduc === 'High School Level' ? 'selected' : ''?>>High School Level</option>
                                        <option value="High School Graduate" <?php $selectedFeduc === 'High School Graduate' ? 'selected' : ''?>>High School Graduate</option>
                                        <option value="Vocational/Technical School" <?php $selectedFeduc === 'Vocational/Technical School' ? 'selected' : ''?>>Vocational/Technical School</option>
                                        <option value="Associate's Degree" <?php $selectedFeduc === "Associate's Degree" ? 'selected' : ''?>>Associate's Degree</option>
                                        <option value="Bachelor's Degree" <?php $selectedFeduc === "Bachelor's Degree" ? 'selected' : ''?>>Bachelor's Degree</option>
                                        <option value="Master's Degree" <?php $selectedFeduc === "Master's Degree" ? 'selected' : ''?>>Master's Degree</option>
                                        <option value="Doctorate/Ph.D." <?php $selectedFeduc === 'Doctorate/Ph.D.' ? 'selected' : ''?>>Doctorate/Ph.D.</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select father's Educational Attainment.
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                    const contactInput = document.getElementById('fathercontactnumber');

                                    contactInput.addEventListener('input', function () {
                                        let value = contactInput.value.replace(/\D/g, '');
                                        
                                        if (value.length > 10) {
                                            value = value.slice(0, 10);
                                        }
                                        
                                        contactInput.value = value;
                                    });
                                });
                                </script>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="fatheroccupation" class="form-label fw-bold">Occupation:</label>
                                        <input type="text" name="txtFatherOccupation" id="fatheroccupation" class="form-control" placeholder="Father's Occupation" value="<?php echo $student['foccu']?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="fatheroffice" class="form-label fw-bold">Office/Business Address:</label>
                                        <input type="text" class="form-control" id="fatheroffice" name="txtFatherOffice" placeholder="(Optional)" value="<?php echo $student['foffice']?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="fatheremail" class="form-label fw-bold">Email:</label>
                                        <input type="email" class="form-control" id="fatheremail" name="txtFatherEmail" placeholder="e.g., juandelacruz@gmail.com"  value="<?php echo $student['femail']?>">
                                        <div class="invalid-feedback">
                                            Please enter the father's email address.
                                        </div>
                                    </div>
                                </div>
                            <!-- Fetched Address for father -->
                                <div class="row mb-4">
                                    <!-- Parent Label for Address of student -->
                                    <input type="checkbox" name="keepFatherAddressCB" id="keepFatherAddressCB" style="display: none;" checked>
                                    <div class="col-12 d-flex align-items-center justify-content-between">
                                        <label for="fatherAddress" class="form-label fw-bold me-2">Address: </label>
                                        <input type="text" id="fatherAddress" name="fatherAddress" value="<?php echo !empty($student['faddress']) ? htmlspecialchars($student['faddress']) : '(Address Not yet defined.)'; ?>" readonly>
                                        <div class="d-flex mb-2">
                                            <a href="#" id="editFatherAddress" class="btn btn-outline-primary btn-sm me-2">
                                                <i class="fas fa-edit"></i> Update Address
                                            </a>
                                            <a href="#" id="cancelEditFatherAddress" class="btn btn-danger btn-sm" style="display: none;">
                                                <i class="fas fa-times"></i> Cancel Updating
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fathers address -->
                                <div class="fatherAdress" id="fatherAdress" style="display: none;">
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control" id="fatherHouseNumber" name="txtfHouseNumber" placeholder="House No." >
                                            <div class="invalid-feedback">
                                                Please enter the house number.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control" id="fatherStreetName" name="txtfStreetName" placeholder="Street Name" >
                                            <div class="invalid-feedback">
                                                Please enter the street name.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control" id="fatherSubdivision" name="txtfSubdivision" placeholder="Subdivision/Village" >
                                            <div class="invalid-feedback">
                                                Please enter the subdivision.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                        <select class="form-select form-control selectpicker" id="fatherRegion" data-live-search="true" name="txtfRegion" >
                                            <option value="" selected disabled>Select Region</option>
                                            <?php
                                            try {
                                                $query = "SELECT regCode, regDesc FROM refregion";
                                                $stmt = $conn->query($query);
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row['regCode'] . '">' . $row['regDesc'] . '</option>';
                                                }
                                            } catch (PDOException $e) {
                                                echo '<option disabled>Error fetching Regions</option>';
                                            }
                                            ?>
                                        </select>
                                            <div class="invalid-feedback">
                                                Please select a region.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                        <select class="form-select form-control selectpicker" id="fatherProvince" data-live-search="true" >
                                            <option value="" selected disabled>Select Province</option>
                                        </select>
                                        <input type="hidden" id="selectedProvinceDescF" name="txtfProvince">
                                            <div class="invalid-feedback">
                                                Please select a province.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                        <select class="form-select form-control selectpicker" id="fatherCity" data-live-search="true" >
                                            <option value="" selected disabled>Select City/Municipality</option>
                                        </select>
                                        <input type="hidden" id="selectedCityDescF" name="txtfCity">
                                            <div class="invalid-feedback">
                                                Please select city or municipality.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <select class="form-select form-control selectpicker" id="fatherBarangay" data-live-search="true" >
                                                <option value="" selected disabled>Select Barangay</option>
                                            </select>
                                            <input type="hidden" id="selectedBarangayDescF" name="txtfBarangay">
                                            <div class="invalid-feedback">
                                                Please select barangay.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="number" class="form-control" id="fatherPostalCode" name="txtfPostalCode" placeholder="Postal Code" >
                                            <div class="invalid-feedback">
                                                Please enter the Postal code.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                                
                        </fieldset>
                        <!-- mother's Information -->
                        <fieldset class="border p-4 rounded mb-4" id="motherField">
                            <legend>Mother's Information</legend>     
                            <legend class="sm"><input type="checkbox" name="motherNoRecord" id="motherNoRecord">
                                <small><label for="motherNoRecord">Mark if the student doesn't have a mother's record.</label></small>
                            </legend>    
                            <div class="motherInfo" id="motherInfo">
                                <!-- Mother Inputs -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                    <label for="motherlname" class="form-label fw-bold">Last Name: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="motherlname" name="txtMotherlname" placeholder="Mother's Last Name" value="<?php echo $student['mlname']?>" required>
                                        <div class="invalid-feedback">
                                            Please enter the mother's Last Name.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <label for="motherfname" class="form-label fw-bold">First Name: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="motherfname" name="txtMotherfname" placeholder="Mother's First Name" value="<?php echo $student['mfname']?>" required>
                                        <div class="invalid-feedback">
                                            Please enter the mother's First Name.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <label for="mothermname" class="form-label fw-bold">Middle Name: </label>
                                        <input type="text" class="form-control" id="mothermname" name="txtMothermname" placeholder="Mother's Middle Name" value="<?php echo $student['mmname']?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <?php
                                        // Fetch the birth year from the database
                                        $birthYear = $student['mdob'] ?? ''; 

                                        // Calculate the age based on the current year
                                        $currentYear = date('Y');
                                        if ($birthYear) {
                                            $age = $currentYear - $birthYear;
                                        } else {
                                            $age = '';
                                        }
                                        ?>
                                    <label for="motherage" class="form-label fw-bold">Age: <span class="required-asterisk">*</span></label>
                                        <input type="number" id="motherage" name="txtMotherAge" class="form-control" placeholder="Mother's Age" value="<?php echo $age ?>" required>
                                        <input type="hidden" id="motherBirthYear" name="txtMotherDOB" value="<?php echo $birthYear; ?>">
                                        <div class="invalid-feedback">
                                            Please enter the mother's Age.
                                        </div>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            function updateAgeAndDOB() {
                                                var motherageInput = document.getElementById('motherage');
                                                var motherBirthYearInput = document.getElementById('motherBirthYear');

                                                // Get the current year
                                                var currentYear = new Date().getFullYear();

                                                // Calculate the birth year based on the age input
                                                var age = parseInt(motherageInput.value, 10);
                                                if (!isNaN(age) && age >= 0) {
                                                    var calculatedBirthYear = currentYear - age;
                                                    motherBirthYearInput.value = calculatedBirthYear;
                                                } else {
                                                    motherBirthYearInput.value = ''; 
                                                }
                                            }

                                            // Add event listeners for updates
                                            document.getElementById('motherage').addEventListener('input', updateAgeAndDOB);

                                            // Initial update on page load if age or birth year is prefilled
                                            updateAgeAndDOB();
                                        });
                                    </script>
                                    <div class="col-md-4">
                                        <label for="mothercontactnumber" class="form-label fw-bold">Contact Number: <span class="required-asterisk">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">(+63)</span>
                                            <input type="text" 
                                            class="form-control" 
                                            id="mothercontactnumber" 
                                            name="txtMotherContactNum" 
                                            pattern="\d{10}" 
                                            placeholder="Enter the last 10-digits" 
                                            value="<?php echo $student['mcontact'] ?? ''?>"
                                            required>
                                            <div class="invalid-feedback">
                                                Please enter a valid mother's contact number.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mothereducatt" class="form-label fw-bold">Educational Attainment: <span class="required-asterisk">*</span></label>
                                        
                                        <?php $selectedMeduc = isset($student['meduc']) ? $student['meduc'] : '';?>

                                        <select class="form-select form-control" name="txtMotherEducAtt" id="mothereducatt" required>
                                        <option disabled value="" <?php echo empty($selectedMeduc) ? 'selected' : ''?>>Select One</option>
                                        <option value="Elementary Level" <?php $selectedMeduc === 'Elementary Level' ? 'selected' : ''?>>Elementary Level</option>
                                        <option value="Elementary Graduate" <?php $selectedMeduc === 'Elementary Graduate' ? 'selected' : ''?>>Elementary Graduate</option>
                                        <option value="High School Level" <?php $selectedMeduc === 'High School Level' ? 'selected' : ''?>>High School Level</option>
                                        <option value="High School Graduate" <?php $selectedMeduc === 'High School Graduate' ? 'selected' : ''?>>High School Graduate</option>
                                        <option value="Vocational/Technical School" <?php $selectedMeduc === 'Vocational/Technical School' ? 'selected' : ''?>>Vocational/Technical School</option>
                                        <option value="Associate's Degree" <?php $selectedMeduc === "Associate's Degree" ? 'selected' : ''?>>Associate's Degree</option>
                                        <option value="Bachelor's Degree" <?php $selectedMeduc === "Bachelor's Degree" ? 'selected' : ''?>>Bachelor's Degree</option>
                                        <option value="Master's Degree" <?php $selectedMeduc === "Master's Degree" ? 'selected' : ''?>>Master's Degree</option>
                                        <option value="Doctorate/Ph.D." <?php $selectedMeduc === 'Doctorate/Ph.D.' ? 'selected' : ''?>>Doctorate/Ph.D.</option>
                                        </select>

                                        <div class="invalid-feedback">
                                            Please select mother's Educational Attainment.
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                    const contactInput = document.getElementById('mothercontactnumber');

                                    contactInput.addEventListener('input', function () {
                                        let value = contactInput.value.replace(/\D/g, '');
                                        
                                        if (value.length > 10) {
                                            value = value.slice(0, 10);
                                        }
                                        
                                        contactInput.value = value;
                                    });
                                });
                                </script>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="motheroccupation" class="form-label fw-bold">Occupation:</label>
                                        <input type="text" name="txtMotherOccupation" id="motheroccupation" class="form-control" placeholder="Mother's Occupation" value="<?php echo $student['moccu']?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="motheroffice" class="form-label fw-bold">Office/Business Address:</label>
                                        <input type="text" class="form-control" id="motheroffice" name="txtMotherOffice" placeholder="(Optional)" value="<?php echo $student['moffice']?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="motheremail" class="form-label fw-bold">Email:</label>
                                        <input type="email" class="form-control" id="motheremail" name="txtMotherEmail" placeholder="e.g., juandelacruz@gmail.com"  value="<?php echo $student['memail']?>">
                                        <div class="invalid-feedback">
                                            Please enter the mother's email address.
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <!-- Parent Label for Address of student -->
                                    <input type="checkbox" name="keepMotherAddressCB" id="keepMotherAddressCB" style="display: none;" checked>
                                    <div class="col-12 d-flex align-items-center justify-content-between">
                                        <label for="motherAddress" class="form-label fw-bold me-2">Address: </label>
                                            <input type="text" id="motherAddress" name="motherAddress" value="<?php echo !empty($student['maddress']) ? htmlspecialchars($student['maddress']) : '(Address Not yet defined.)'; ?>" readonly>
                                        <div class="d-flex mb-2">
                                            <a href="#" id="editMotherAddress" class="btn btn-outline-primary btn-sm me-2">
                                                <i class="fas fa-edit"></i> Update Address
                                            </a>
                                            <a href="#" id="cancelEditMotherAddress" class="btn btn-danger btn-sm" style="display: none;">
                                                <i class="fas fa-times"></i> Cancel Updating
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Mothers address -->
                                <div class="motherAdress" id="motherAdress" style="display: none;">
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control" id="motherHouseNumber" name="txtmHouseNumber" placeholder="House No." >
                                            <div class="invalid-feedback">
                                                Please enter the house number.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control" id="motherStreetName" name="txtmStreetName" placeholder="Street Name" required>
                                            <div class="invalid-feedback">
                                                Please enter the street name.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="form-control" id="motherSubdivision" name="txtmSubdivision" placeholder="Subdivision/Village" required>
                                            <div class="invalid-feedback">
                                                Please enter the subdivision.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                        <select class="form-select form-control selectpicker" id="motherRegion" data-live-search="true" name="txtmRegion" required>
                                            <option value="" selected disabled>Select Region</option>
                                            <?php
                                                try {
                                                    $query = "SELECT regCode, regDesc FROM refregion";
                                                    $stmt = $conn->query($query);
                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        echo '<option value="' . $row['regCode'] . '">' . $row['regDesc'] . '</option>';
                                                    }
                                                } catch (PDOException $e) {
                                                    echo '<option disabled>Error fetching Regions</option>';
                                                }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a region.
                                        </div>
                                    </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                        <select class="form-select form-control selectpicker" id="motherProvince" data-live-search="true" required>
                                            <option value="" selected disabled>Select Province</option>
                                        </select>
                                        <input type="hidden" id="selectedProvinceDescM" name="txtmProvince">
                                            <div class="invalid-feedback">
                                                Please select a province.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                        <select class="form-select form-control selectpicker" id="motherCity" data-live-search="true" required>
                                            <option value="" selected disabled>Select City/Municipality</option>
                                        </select>
                                        <input type="hidden" id="selectedCityDescM" name="txtmCity">
                                            <div class="invalid-feedback">
                                                Please select city or municipality.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6 mb-3">
                                            <select class="form-select form-control selectpicker" id="motherBarangay" data-live-search="true" required>
                                                <option value="" selected disabled>Select Barangay</option>
                                            </select>
                                            <input type="hidden" id="selectedBarangayDescM" name="txtmBarangay">
                                            <div class="invalid-feedback">
                                                Please select barangay.
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="number" class="form-control" id="motherPostalCode" name="txtmPostalCode" placeholder="Postal Code" required>
                                            <div class="invalid-feedback">
                                                Please enter the Postal code.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- guardian's Information -->
                        <fieldset class="border p-4 rounded mb-4" id="gdField">
                            <legend>Guardian's Information</legend>     

                            <div class="row mb-4">
                                <div class="col-md-6">
                                <label for="selGuardian" class="form-label fw-bold">Select Guardian: <span class="required-asterisk">*</span></label>
                                    <select class="form-select form-control" name="selGuardian" id="selGuardian" required>
                                        <option value="Father">Father</option>
                                        <option value="Mother">Mother</option>
                                        <option value="others">Others</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a guardian.
                                    </div>
                                </div>
                            </div>
                            <div class="gdInfo" id="gdInfo" style="display: none">
                                <!-- Guardian Inputs -->
                                <div class="row mb-4">
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                        <label for="gdlname" class="form-label fw-bold">Last Name: <span class="required-asterisk">*</span></label>
                                            <input type="text" class="form-control" id="gdlname" name="txtGdlname" placeholder="Guardian's Last Name">
                                            <div class="invalid-feedback">
                                                Please enter the guardian's Last Name.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <label for="gdfname" class="form-label fw-bold">First Name: <span class="required-asterisk">*</span></label>
                                            <input type="text" class="form-control" id="gdfname" name="txtGdfname" placeholder="Guardian's First Name" >
                                            <div class="invalid-feedback">
                                                Please enter the guardian's First Name.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <label for="gdmname" class="form-label fw-bold">MIddle Name: </label>
                                            <input type="text" class="form-control" id="gdmname" name="txtGdmname" placeholder="Guardian's Middle Name" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" for="otherRelationship">Other Guardian: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" name="otherRelationship" id="otherRelationship" placeholder="Specify Relationship">
                                        <div class="invalid-feedback">
                                            Please specify the relationship.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gdcontactnumber" class="form-label fw-bold">Contact Number: <span class="required-asterisk">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">(+63)</span>
                                            <input type="text" 
                                            class="form-control" 
                                            id="gdcontactnumber" 
                                            name="txtGdContactNum" 
                                            pattern="\d{10}" 
                                            placeholder="Enter the last 10-digits" 
                                            >
                                            <div class="invalid-feedback">
                                                Please enter a valid guardian's contact number.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                      
                    </fieldset>
                    <script>
                        var isFatherInfoMissing = <?php echo json_encode($isFatherInfoMissing); ?>;
                        var isMotherInfoMissing = <?php echo json_encode($isMotherInfoMissing); ?>;

                        // If true, check the checkbox
                        if (isFatherInfoMissing) {
                            document.getElementById('fatherNoRecord').checked = true;
                        }
                        if (isMotherInfoMissing) {
                            document.getElementById('motherNoRecord').checked = true;
                        }
                    </script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const contactInput = document.getElementById('gdcontactnumber');
                            contactInput.addEventListener('input', function () {
                                let value = contactInput.value.replace(/\D/g, '');
                                
                                if (value.length > 10) {
                                    value = value.slice(0, 10);
                                }
                                
                                contactInput.value = value;
                            });
                            
                            // Function to update guardian options based on checkbox status
                            function updateGuardianOptions() {
                                // Get the select element and checkboxes
                                var selGuardian = document.getElementById('selGuardian');
                                var fatherNoRecord = document.getElementById('fatherNoRecord').checked;
                                var motherNoRecord = document.getElementById('motherNoRecord').checked;

                                // Clear existing options
                                selGuardian.innerHTML = '';

                                // Add the default option
                                var defaultOption = document.createElement('option');
                                defaultOption.value = '';
                                defaultOption.textContent = 'Select a Guardian';
                                defaultOption.disabled = true;
                                defaultOption.selected = true;
                                selGuardian.appendChild(defaultOption);

                                // Flag to check if options are available
                                var fatherOptionAdded = false;
                                var motherOptionAdded = false;

                                // Add Father and Mother options based on checkbox status
                                if (!fatherNoRecord) {
                                    var optionFather = document.createElement('option');
                                    optionFather.value = 'Father';
                                    optionFather.textContent = 'Father';
                                    selGuardian.appendChild(optionFather);
                                    fatherOptionAdded = true;
                                }

                                if (!motherNoRecord) {
                                    var optionMother = document.createElement('option');
                                    optionMother.value = 'Mother';
                                    optionMother.textContent = 'Mother';
                                    selGuardian.appendChild(optionMother);
                                    motherOptionAdded = true;
                                }

                                // Add the "Others" option
                                var optionOthers = document.createElement('option');
                                optionOthers.value = 'others';
                                optionOthers.textContent = 'Others';
                                selGuardian.appendChild(optionOthers);

                                // Determine which option to select based on available options
                                if (fatherOptionAdded && motherOptionAdded) {
                                    // Both Father and Mother are available
                                    selGuardian.value = ''; // Preselect the default option
                                } else if (fatherOptionAdded) {
                                    selGuardian.value = 'Father'; // If only Father is available
                                } else if (motherOptionAdded) {
                                    selGuardian.value = 'Mother'; // If only Mother is available
                                } else {
                                    selGuardian.value = 'others'; // If neither Father nor Mother is available
                                }

                                // Show or hide the gdInfo section based on selected value
                                var gdInfo = document.getElementById('gdInfo');
                                if (selGuardian.value === 'others') {
                                    gdInfo.style.display = 'block';
                                    // Set required attributes for fields except middle name
                                    document.getElementById('gdlname').required = true;
                                    document.getElementById('gdfname').required = true;
                                    document.getElementById('gdcontactnumber').required = true;
                                    document.getElementById('otherRelationship').required = true;
                                    document.getElementById('gdmname').required = false; // Middle name is not required
                                } else {
                                    gdInfo.style.display = 'none';
                                    // Remove required attributes when not showing the gdInfo section
                                    document.getElementById('gdlname').required = false;
                                    document.getElementById('gdfname').required = false;
                                    document.getElementById('gdcontactnumber').required = false;
                                    document.getElementById('otherRelationship').required = false;
                                }
                            }

                            // Initial update on page load
                            updateGuardianOptions();

                            // Update options and gdInfo visibility when checkboxes or select element change
                            document.getElementById('fatherNoRecord').addEventListener('change', updateGuardianOptions);
                            document.getElementById('motherNoRecord').addEventListener('change', updateGuardianOptions);
                            document.getElementById('selGuardian').addEventListener('change', function() {
                                var gdInfo = document.getElementById('gdInfo');
                                if (this.value === 'others') {
                                    gdInfo.style.display = 'block';
                                    document.getElementById('gdlname').required = true;
                                    document.getElementById('gdfname').required = true;
                                    document.getElementById('gdcontactnumber').required = true;
                                    document.getElementById('otherRelationship').required = true;
                                    document.getElementById('gdmname').required = false;
                                } else {
                                    gdInfo.style.display = 'none';
                                    document.getElementById('gdlname').required = false;
                                    document.getElementById('gdfname').required = false;
                                    document.getElementById('gdcontactnumber').required = false;
                                    document.getElementById('otherRelationship').required = false;
                                }
                            });
                        });
                    </script>








<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Father Address Script
    const editFatherAddressBtn = document.getElementById('editFatherAddress');
    const cancelEditFatherAddressBtn = document.getElementById('cancelEditFatherAddress');
    const fatherAddressField = document.getElementById('fatherAdress');
    const keepFatherAddressCB = document.getElementById('keepFatherAddressCB');
    
    // Initialize the father's address fields to be hidden
    fatherAddressField.style.display = 'none';
    
    editFatherAddressBtn.addEventListener('click', function (e) {
        e.preventDefault();
        fatherAddressField.style.display = 'block';
        editFatherAddressBtn.style.display = 'none';
        cancelEditFatherAddressBtn.style.display = 'inline-block';
        keepFatherAddressCB.checked = false; // Uncheck the checkbox when editing
        toggleFatherAddressFields();
    });

    cancelEditFatherAddressBtn.addEventListener('click', function (e) {
        e.preventDefault();
        fatherAddressField.style.display = 'none';
        editFatherAddressBtn.style.display = 'inline-block';
        cancelEditFatherAddressBtn.style.display = 'none';
        keepFatherAddressCB.checked = true; // Re-check the checkbox when canceling
        toggleFatherAddressFields();
    });

    // mother Address Script
    const editMotherAddressBtn = document.getElementById('editMotherAddress');
    const cancelEditMotherAddressBtn = document.getElementById('cancelEditMotherAddress');
    const motherAddressField = document.getElementById('motherAdress');
    const keepMotherAddressCB = document.getElementById('keepMotherAddressCB');
    
    // Initialize the mother's address fields to be hidden
    motherAddressField.style.display = 'none';
    
    editMotherAddressBtn.addEventListener('click', function (e) {
        e.preventDefault();
        motherAddressField.style.display = 'block';
        editMotherAddressBtn.style.display = 'none';
        cancelEditMotherAddressBtn.style.display = 'inline-block';
        keepMotherAddressCB.checked = false; // Uncheck the checkbox when editing
        toggleMotherAddressFields();
    });

    cancelEditMotherAddressBtn.addEventListener('click', function (e) {
        e.preventDefault();
        motherAddressField.style.display = 'none';
        editMotherAddressBtn.style.display = 'inline-block';
        cancelEditMotherAddressBtn.style.display = 'none';
        keepMotherAddressCB.checked = true; // Re-check the checkbox when canceling
        toggleMotherAddressFields();
    });

    // Student Address Script
    const editAddressBtn = document.getElementById('editAddress');
    const cancelEditAddressBtn = document.getElementById('cancelEditAddress');
    const addressSection = document.getElementById('address');
    const studentAddressSection = document.getElementById('studentAddress');
    const keepAddressCB = document.getElementById('keepAddressCB');
    const addressFields = document.querySelectorAll('#houseNumber, #streetName, #subdivision, #region, #province, #city, #barangay, #postalCode');

    editAddressBtn.addEventListener('click', function(e) {
        e.preventDefault();
        addressSection.style.display = 'block';
        studentAddressSection.style.display = 'none';
        this.style.display = 'none';
        cancelEditAddressBtn.style.display = 'inline-block';
        keepAddressCB.checked = false; // Uncheck the checkbox when editing
        toggleAddressFields();
    });

    cancelEditAddressBtn.addEventListener('click', function(e) {
        e.preventDefault();
        addressSection.style.display = 'none';
        studentAddressSection.style.display = 'block';
        this.style.display = 'none';
        editAddressBtn.style.display = 'inline-block';
        keepAddressCB.checked = true; // Re-check the checkbox when canceling
        toggleAddressFields();
    });

    function toggleAddressFields() {
        if (keepAddressCB.checked) {
            // Remove required attribute from address fields
            addressFields.forEach(field => {
                field.removeAttribute('required');
            });
        } else {
            // Add required attribute to address fields
            addressFields.forEach(field => {
                if (!field.hasAttribute('required')) {
                    field.setAttribute('required', '');
                }
            });
        }
    }

    function toggleFatherAddressFields() {
        const fatherAddressFields = document.querySelectorAll('#fatherHouseNumber, #fatherStreetName, #fatherSubdivision, #fatherRegion, #fatherProvince, #fatherCity, #fatherBarangay, #fatherPostalCode');
        if (keepFatherAddressCB.checked) {
            fatherAddressFields.forEach(field => field.removeAttribute('required'));
        } else {
            fatherAddressFields.forEach(field => {
                if (!field.hasAttribute('required')) {
                    field.setAttribute('required', '');
                }
            });
        }
    }
    function toggleMotherAddressFields() {
        const motherAddressFields = document.querySelectorAll('#motherHouseNumber, #motherStreetName, #motherSubdivision, #motherRegion, #motherProvince, #motherCity, #motherBarangay, #motherPostalCode');
        if (keepMotherAddressCB.checked) {
            motherAddressFields.forEach(field => field.removeAttribute('required'));
        } else {
            motherAddressFields.forEach(field => {
                if (!field.hasAttribute('required')) {
                    field.setAttribute('required', '');
                }
            });
        }
    }

    // Father No Record Checkbox Script
    const fatherNoRecordCB = document.getElementById('fatherNoRecord');
    const fatherInfo = document.getElementById('fatherInfo');
    const fatherPersonalFields = document.querySelectorAll('#fatherlname, #fatherfname, #fatherage, #fathercontactnumber, #fathereducatt');
    const fatherAdress = document.getElementById('fatherAdress');

    function toggleFatherInfoFields() {
        if (fatherNoRecordCB.checked) {
            fatherInfo.style.display = 'none';
            fatherAdress.style.display = 'none'; // Keep hidden if 'No Record' is checked

            // Remove required attributes from father's fields
            fatherPersonalFields.forEach(field => field.removeAttribute('required'));
            toggleFatherAddressFields(); // Make sure address fields also lose required status
        } else {
            fatherInfo.style.display = 'block';

            // Add required attributes back on father's fields
            fatherPersonalFields.forEach(field => {
                if (!field.hasAttribute('required')) {
                    field.setAttribute('required', '');
                }
            });

            // Display the address fields if the user is editing them
            if (editFatherAddressBtn.style.display === 'none') {
                fatherAdress.style.display = 'block';
            } else {
                fatherAdress.style.display = 'none';
            }

            // Re-apply the required attributes based on the checkbox state for the address
            toggleFatherAddressFields();
        }
    }

    // Mother No Record Checkbox Script
    const motherNoRecordCB = document.getElementById('motherNoRecord');
    const motherInfo = document.getElementById('motherInfo');
    const motherPersonalFields = document.querySelectorAll('#motherlname, #motherfname, #motherage, #mothercontactnumber, #mothereducatt');
    const motherAdress = document.getElementById('motherAdress');

    function toggleMotherInfoFields() {
        if (motherNoRecordCB.checked) {
            motherInfo.style.display = 'none';
            motherAdress.style.display = 'none'; // Keep hidden if 'No Record' is checked

            // Remove required attributes from mother's fields
            motherPersonalFields.forEach(field => field.removeAttribute('required'));
            toggleMotherAddressFields(); // Make sure address fields also lose required status
        } else {
            motherInfo.style.display = 'block';

            // Add required attributes back on mother's fields
            motherPersonalFields.forEach(field => {
                if (!field.hasAttribute('required')) {
                    field.setAttribute('required', '');
                }
            });

            // Display the address fields if the user is editing them
            if (editMotherAddressBtn.style.display === 'none') {
                motherAdress.style.display = 'block';
            } else {
                motherAdress.style.display = 'none';
            }

            // Re-apply the required attributes based on the checkbox state for the address
            toggleMotherAddressFields();
        }
    }





    // Initialize the fields based on the checkbox state on page load
    toggleAddressFields();
    toggleFatherAddressFields();
    toggleFatherInfoFields();  
    toggleMotherAddressFields();
    toggleMotherInfoFields();  

    // Update fields when checkbox state changes
    keepAddressCB.addEventListener('change', toggleAddressFields);
    keepFatherAddressCB.addEventListener('change', toggleFatherAddressFields);
    keepMotherAddressCB.addEventListener('change', toggleMotherAddressFields);
    fatherNoRecordCB.addEventListener('change', toggleFatherInfoFields);
    motherNoRecordCB.addEventListener('change', toggleMotherInfoFields);
});

</script>









                        <div class="col-12">
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary m-1" name="updateStudentBtn">Update</button>
                                <button type="reset" class="btn btn-info m-1">Reset</button>
                                <a href="manage_studentsRec.php" class="btn btn-secondary m-1">Cancel</a>
                            </div>
                        </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

    <?php require_once "support/footer.php"?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="assets/sweetalert2.all.min.js"></script>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.min.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

<script>
    // Show the image preview
    function previewImage(event) {
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imagePreview = document.getElementById('imagePreview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block'; 
            };
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var dateOfBirthInput = document.getElementById('dateofbirth');
        var ageInput = document.getElementById('age');

        dateOfBirthInput.addEventListener('change', function() {
            var dob = new Date(dateOfBirthInput.value);
            var age = calculateAge(dob);
            ageInput.value = age;
        });

        function calculateAge(dob) {
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var monthDifference = today.getMonth() - dob.getMonth();
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            return age;
        }
    });
    $(document).ready(function() {
        // Initially disable province, city, and barangay fields
        $('#fatherProvince').prop('disabled', true).selectpicker('refresh');
        $('#fatherCity').prop('disabled', true).selectpicker('refresh');
        $('#fatherBarangay').prop('disabled', true).selectpicker('refresh');

        $('#fatherRegion').on('change', function() {
            var regCode = $(this).val();
            if (regCode) {
                $.ajax({
                    url: 'fetch/get_province.php',
                    type: 'POST',
                    data: { regCode: regCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#fatherProvince').empty();
                        $('#fatherProvince').append('<option value="" selected disabled>Select Province</option>');
                        $.each(data, function(index, province) {
                            $('#fatherProvince').append('<option value="' + province.provCode + '" data-desc="' + province.provDesc + '">' + province.provDesc + '</option>');
                        });
                        $('#fatherProvince').prop('disabled', false).selectpicker('refresh');
                    }
                });
            } else {
                $('#fatherProvince').empty().append('<option value="" selected disabled>Select Province</option>').prop('disabled', true).selectpicker('refresh');
                $('#fatherCity').empty().append('<option value="" selected disabled>Select City/Municipality</option>').prop('disabled', true).selectpicker('refresh');
                $('#fatherBarangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).selectpicker('refresh');
            }
        });

        $('#fatherProvince').on('change', function() {
            var provCode = $(this).val();
            var provDesc = $('#fatherProvince option:selected').data('desc'); // Get descriptive name
            $('#selectedProvinceDescF').val(provDesc); // Save it in a hidden field
            if (provCode) {
                $.ajax({
                    url: 'fetch/get_cities.php',
              type: 'POST',
                    data: { provCode: provCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#fatherCity').empty();
                        $('#fatherCity').append('<option value="" selected disabled>Select City/Municipality</option>');
                        $.each(data, function(index, city) {
                            $('#fatherCity').append('<option value="' + city.citymunCode + '" data-desc="' + city.citymunDesc + '">' + city.citymunDesc + '</option>');
                        });
                        $('#fatherCity').prop('disabled', false).selectpicker('refresh');
                    }
                });
            } else {
                $('#fatherCity').empty().append('<option value="" selected disabled>Select City/Municipality</option>').prop('disabled', true).selectpicker('refresh');
                $('#fatherBarangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).selectpicker('refresh');
            }
        });

        $('#fatherCity').on('change', function() {
            var citymunCode = $(this).val();
            var citymunDesc = $('#fatherCity option:selected').data('desc'); // Get descriptive name
            $('#selectedCityDescF').val(citymunDesc); // Save it in a hidden field
            if (citymunCode) {
                $.ajax({
                    url: 'fetch/get_brgy.php',
                    type: 'POST',
                    data: { citymunCode: citymunCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#fatherBarangay').empty();
                        $('#fatherBarangay').append('<option value="" selected disabled>Select Barangay</option>');
                        $.each(data, function(index, barangay) {
                            $('#fatherBarangay').append('<option value="' + barangay.brgyCode + '" data-desc="' + barangay.brgyDesc + '">' + barangay.brgyDesc + '</option>');
                        });
                        $('#fatherBarangay').prop('disabled', false).selectpicker('refresh');
                    }
                });
            } else {
                $('#fatherBarangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).selectpicker('refresh');
            }
        });

        $('#fatherBarangay').on('change', function() {
            var brgyDesc = $('#fatherBarangay option:selected').data('desc'); // Get descriptive name
            $('#selectedBarangayDescF').val(brgyDesc); // Save it in a hidden field
        });

        
        // Initially disable province, city, and barangay fields and set hover tooltip
        $('#province').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
        $('#city').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
        $('#barangay').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');

        $('#region').on('change', function() {
            var regCode = $(this).val();
            if (regCode) {
                $.ajax({
                    url: 'fetch/get_province.php',
                    type: 'POST',
                    data: { regCode: regCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#province').empty();
                        $('#province').append('<option value="" selected disabled>Select Province</option>');
                        $.each(data, function(index, province) {
                            $('#province').append('<option value="' + province.provCode + '" data-desc="' + province.provDesc + '">' + province.provDesc + '</option>');
                        });
                        $('#province').prop('disabled', false).attr('title', '').selectpicker('refresh');
                    }
                });
            } else {
                $('#province').empty().append('<option value="" selected disabled>Select Province</option>').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
                $('#city').empty().append('<option value="" selected disabled>Select City/Municipality</option>').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
                $('#barangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
            }
        });

        $('#province').on('change', function() {
            var provCode = $(this).val();
            var provDesc = $('#province option:selected').data('desc'); // Get descriptive name
            $('#selectedProvinceDesc').val(provDesc); // Save it in a hidden field
            if (provCode) {
                $.ajax({
                    url: 'fetch/get_cities.php',
                    type: 'POST',
                    data: { provCode: provCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#city').empty();
                        $('#city').append('<option value="" selected disabled>Select City/Municipality</option>');
                        $.each(data, function(index, city) {
                            $('#city').append('<option value="' + city.citymunCode + '" data-desc="' + city.citymunDesc + '">' + city.citymunDesc + '</option>');
                        });
                        $('#city').prop('disabled', false).attr('title', '').selectpicker('refresh');
                    }
                });
            } else {
                $('#city').empty().append('<option value="" selected disabled>Select City/Municipality</option>').prop('disabled', true).attr('title', 'Select province first').selectpicker('refresh');
                $('#barangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).attr('title', 'Select province first').selectpicker('refresh');
            }
        });

        $('#city').on('change', function() {
            var citymunCode = $(this).val();
            var citymunDesc = $('#city option:selected').data('desc'); // Get descriptive name
            $('#selectedCityDesc').val(citymunDesc); // Save it in a hidden field
            if (citymunCode) {
                $.ajax({
                    url: 'fetch/get_brgy.php',
                    type: 'POST',
                    data: { citymunCode: citymunCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#barangay').empty();
                        $('#barangay').append('<option value="" selected disabled>Select Barangay</option>');
                        $.each(data, function(index, barangay) {
                            $('#barangay').append('<option value="' + barangay.brgyCode + '" data-desc="' + barangay.brgyDesc + '">' + barangay.brgyDesc + '</option>');
                        });
                        $('#barangay').prop('disabled', false).attr('title', '').selectpicker('refresh');
                    }
                });
            } else {
                $('#barangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).attr('title', 'Select city first').selectpicker('refresh');
            }
        });

        $('#barangay').on('change', function() {
            var brgyDesc = $('#barangay option:selected').data('desc'); // Get descriptive name
            $('#selectedBarangayDesc').val(brgyDesc); // Save it in a hidden field
        });
        
        
        // Initially disable province, city, and barangay fields and set hover tooltip
        $('#motherProvince').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
        $('#motherCity').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
        $('#motherBarangay').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');

        $('#motherRegion').on('change', function() {
            var regCode = $(this).val();
            if (regCode) {
                $.ajax({
                    url: 'fetch/get_province.php',
                    type: 'POST',
                    data: { regCode: regCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#motherProvince').empty();
                        $('#motherProvince').append('<option value="" selected disabled>Select Province</option>');
                        $.each(data, function(index, province) {
                            $('#motherProvince').append('<option value="' + province.provCode + '" data-desc="' + province.provDesc + '">' + province.provDesc + '</option>');
                        });
                        $('#motherProvince').prop('disabled', false).attr('title', '').selectpicker('refresh');
                    }
                });
            } else {
                $('#motherProvince').empty().append('<option value="" selected disabled>Select Province</option>').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
                $('#motherCity').empty().append('<option value="" selected disabled>Select City/Municipality</option>').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
                $('#motherBarangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
            }
        });

        $('#motherProvince').on('change', function() {
            var provCode = $(this).val();
            var provDesc = $('#motherProvince option:selected').data('desc'); // Get descriptive name
            $('#selectedProvinceDescM').val(provDesc); // Save it in a hidden field
            if (provCode) {
                $.ajax({
                    url: 'fetch/get_cities.php',
                    type: 'POST',
                    data: { provCode: provCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#motherCity').empty();
                        $('#motherCity').append('<option value="" selected disabled>Select City/Municipality</option>');
                        $.each(data, function(index, city) {
                            $('#motherCity').append('<option value="' + city.citymunCode + '" data-desc="' + city.citymunDesc + '">' + city.citymunDesc + '</option>');
                        });
                        $('#motherCity').prop('disabled', false).attr('title', '').selectpicker('refresh');
                    }
                });
            } else {
                $('#motherCity').empty().append('<option value="" selected disabled>Select City/Municipality</option>').prop('disabled', true).attr('title', 'Select province first').selectpicker('refresh');
                $('#motherBarangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).attr('title', 'Select province first').selectpicker('refresh');
            }
        });

        $('#motherCity').on('change', function() {
            var citymunCode = $(this).val();
            var citymunDesc = $('#motherCity option:selected').data('desc'); // Get descriptive name
            $('#selectedCityDescM').val(citymunDesc); // Save it in a hidden field
            if (citymunCode) {
                $.ajax({
                    url: 'fetch/get_brgy.php',
                    type: 'POST',
                    data: { citymunCode: citymunCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#motherBarangay').empty();
                        $('#motherBarangay').append('<option value="" selected disabled>Select Barangay</option>');
                        $.each(data, function(index, barangay) {
                            $('#motherBarangay').append('<option value="' + barangay.brgyCode + '" data-desc="' + barangay.brgyDesc + '">' + barangay.brgyDesc + '</option>');
                        });
                        $('#motherBarangay').prop('disabled', false).attr('title', '').selectpicker('refresh');
                    }
                });
            } else {
                $('#motherBarangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).attr('title', 'Select city first').selectpicker('refresh');
            }
        });

        $('#motherBarangay').on('change', function() {
            var brgyDesc = $('#motherBarangay option:selected').data('desc'); // Get descriptive name
            $('#selectedBarangayDescM').val(brgyDesc); // Save it in a hidden field
        });
        
        // Initially disable province, city, and barangay fields and set hover tooltip
        $('#gdProvince').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
        $('#gdCity').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
        $('#gdBarangay').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');

        $('#gdRegion').on('change', function() {
            var regCode = $(this).val();
            if (regCode) {
                $.ajax({
                    url: 'fetch/get_province.php',
                    type: 'POST',
                    data: { regCode: regCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#gdProvince').empty();
                        $('#gdProvince').append('<option value="" selected disabled>Select Province</option>');
                        $.each(data, function(index, province) {
                            $('#gdProvince').append('<option value="' + province.provCode + '" data-desc="' + province.provDesc + '">' + province.provDesc + '</option>');
                        });
                        $('#gdProvince').prop('disabled', false).attr('title', '').selectpicker('refresh');
                    }
                });
            } else {
                $('#gdProvince').empty().append('<option value="" selected disabled>Select Province</option>').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
                $('#gdCity').empty().append('<option value="" selected disabled>Select City/Municipality</option>').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
                $('#gdBarangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).attr('title', 'Select region first').selectpicker('refresh');
            }
        });

        $('#gdProvince').on('change', function() {
            var provCode = $(this).val();
            var provDesc = $('#gdProvince option:selected').data('desc'); // Get descriptive name
            $('#selectedProvinceDescG').val(provDesc); // Save it in a hidden field
            if (provCode) {
                $.ajax({
                    url: 'fetch/get_cities.php',
                    type: 'POST',
                    data: { provCode: provCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#gdCity').empty();
                        $('#gdCity').append('<option value="" selected disabled>Select City/Municipality</option>');
                        $.each(data, function(index, city) {
                            $('#gdCity').append('<option value="' + city.citymunCode + '" data-desc="' + city.citymunDesc + '">' + city.citymunDesc + '</option>');
                        });
                        $('#gdCity').prop('disabled', false).attr('title', '').selectpicker('refresh');
                    }
                });
            } else {
                $('#gdCity').empty().append('<option value="" selected disabled>Select City/Municipality</option>').prop('disabled', true).attr('title', 'Select province first').selectpicker('refresh');
                $('#gdBarangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).attr('title', 'Select province first').selectpicker('refresh');
            }
        });

        $('#gdCity').on('change', function() {
            var citymunCode = $(this).val();
            var citymunDesc = $('#gdCity option:selected').data('desc'); // Get descriptive name
            $('#selectedCityDescG').val(citymunDesc); // Save it in a hidden field
            if (citymunCode) {
                $.ajax({
                    url: 'fetch/get_brgy.php',
                    type: 'POST',
                    data: { citymunCode: citymunCode },
                    dataType: 'json',
                    success: function(data) {
                        $('#gdBarangay').empty();
                        $('#gdBarangay').append('<option value="" selected disabled>Select Barangay</option>');
                        $.each(data, function(index, barangay) {
                            $('#gdBarangay').append('<option value="' + barangay.brgyCode + '" data-desc="' + barangay.brgyDesc + '">' + barangay.brgyDesc + '</option>');
                        });
                        $('#gdBarangay').prop('disabled', false).attr('title', '').selectpicker('refresh');
                    }
                });
            } else {
                $('#gdBarangay').empty().append('<option value="" selected disabled>Select Barangay</option>').prop('disabled', true).attr('title', 'Select city first').selectpicker('refresh');
            }
        });

        $('#gdBarangay').on('change', function() {
            var brgyDesc = $('#gdBarangay option:selected').data('desc'); // Get descriptive name
            $('#selectedBarangayDescG').val(brgyDesc); // Save it in a hidden field
        });
    });
</script>
</body>

</html>
