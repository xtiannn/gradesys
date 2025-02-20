<?php
include 'session.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Students' Information Form</title>
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

    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"> -->
    

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
            border: 2px solid #1a237e;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }
        fieldset:not(.d-none) {
            display: block;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        legend {
            font-size: 24px;
            font-weight: bold;
            color: #1a237e; 
            border-bottom: 2px solid #1a237e; 
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
    background-color: #fff !important;
    color: #000; 
    border: 1px solid #ced4da; 
    cursor: text;
}
.sm{
    font-size: 20px;
    border-bottom: 0px solid #1a237e; 
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

    <main id="main" class="main mt-0">
        <section class="section">
            <div class="custom-container">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="studentForm" action="save_student.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <fieldset class="border p-4 rounded mb-4" id="studentField">
                                <legend>Student Information</legend>
                                <div class="row mb-4">
                                    <!-- Photo Input -->
                                    <div class="col-md-6 mb-3 mt-3">
                                        <label for="photo" class="form-label fw-bold">Select a Photo:</label>
                                        <div class="input-group" style="border: 1px solid #ccc; border-radius: 5px; overflow: hidden;">
                                            <input type="file" id="photo" name="txtPhoto" class="form-control" accept="image/*" onchange="previewImage(event)" style="border: none;" >
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('photo').click()">Browse</button>
                                        </div>
                                    </div>
                                    <!-- Image Preview -->
                                    <div class="col-md-5 mb-3 d-flex justify-content-end position-relative">
                                        <div class="mt-2">
                                            <img id="imagePreview" src="assets/img/user.png" alt="Preview" class="rounded" style="position: absolute; top: 0; right: 0; width: 200px; height: 180px; border: 1px solid #ccc; padding: 0; margin: 0;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <!-- LRN Input -->
                                    <div class="col-md-8 mb-3">
                                        <label for="lrn" class="form-label fw-bold">Learner Reference Number: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="lrn" name="txtStudentLRN" placeholder="Student's LRN" maxlength="12" required
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);">
                                        <div id="lrn-feedback" class="invalid-feedback" style="display: none;">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid 12-digit LRN.
                                        </div>
                                    </div>
                                </div>

                                <!-- Name Inputs -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="studentlname" class="form-label fw-bold">Last Name: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="studentlname" name="txtStudentlname" placeholder="e.g., Dela Cruz" required >
                                        <div class="invalid-feedback">
                                        Please enter Last Name.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="studentfname" class="form-label fw-bold">First Name: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="studentfname" name="txtStudentfname" placeholder="e.g., Juan"  required>
                                        <div class="invalid-feedback">
                                            Please enter First Name.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="studentmname" class="form-label fw-bold mb-2 mt-1">Middle Name: </label>
                                        <input type="text" class="form-control" id="studentmname" name="txtStudentmname">
                                    </div>
                                </div>
                                <!-- Gender and Contact Number Inputs -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="gender" class="form-label fw-bold">Gender: <span class="required-asterisk">*</span></label>
                                        <select class="form-select form-control" id="gender" name="txtStudentGender" required>
                                            <option value="" selected disabled>Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a gender.
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="dateofbirth" class="form-label fw-bold">Date of Birth: <span class="required-asterisk">*</span></label>
                                        <input type="date" class="form-control" name="dtDateOfBirth" id="dateofbirth" required>
                                        <div class="invalid-feedback">
                                            Please enter a valid Date of Birth (cannot be today or a future date).
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
                                        <input type="number" name="txtAge" id="age" class="form-control no-readonly-color"  readonly>
                                        <div class="invalid-feedback">
                                            Please enter age.
                                        </div>
                                    </div>
                                </div>
                                <!-- Date of Birth and Place of Birth Inputs -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="placeofbirth" class="form-label fw-bold">Place of Birth: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" name="txtPlaceOfBirth" id="placeofbirth" placeholder="Enter Province" required>
                                        <div class="invalid-feedback">
                                            Please enter place of birth.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="contactnum" class="form-label fw-bold">Contact Number: </label>
                                        <div class="input-group">
                                            <span class="input-group-text">+63</span>
                                            <input type="text" 
                                                class="form-control" 
                                                name="txtContactNum" 
                                                id="contactnum" 
                                                placeholder="Enter the Last 10-digits" 
                                                maxlength="10" 
                                                
                                                oninput="validateContactNumber(this)">
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter a valid 10-digit contact number starting with "9".
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const contactInput = document.getElementById('contactnum');

                                        contactInput.addEventListener('input', function () {
                                            let value = contactInput.value.replace(/\D/g, '');

                                            if (value.length > 0 && value[0] === '0') {
                                                value = value.slice(1);
                                            }

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
                                    <div class="col-12">
                                        <label for="completeAddress" class="form-label fw-bold">Complete Address: <span class="required-asterisk">*</span></label>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <!-- House Number, Street Name, Barangay, Subdivision/Village -->
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control" id="houseNumber" name="txtHouseNumber" placeholder="House No." required>
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
                                    <select class="form-select form-control selectpicker" id="region" data-live-search="true" required>
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
                                    <select class="form-select form-control selectpicker" id="province" data-live-search="true" required>
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
                                        <select class="form-select form-control selectpicker" id="barangay" data-live-search="true" required>
                                            <option value="" selected disabled>Select Barangay</option>
                                        </select>
                                        <input type="hidden" id="selectedBarangayDesc" name="txtBarangay"  value="">
                                        <div class="invalid-feedback">
                                            Please select barangay.
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="number" class="form-control" id="postalCode" name="txtPostalCode" placeholder="Postal Code" required>
                                        <div class="invalid-feedback">
                                            Please enter the Postal code.
                                        </div>
                                    </div>
                                </div>
                                <!-- Nationality Inputs -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="nationality" class="form-label fw-bold">Nationality: <span class="required-asterisk">*</span></label>
                                        <select class="form-control form-select" name="selNationality" id="nationality" onchange="toggleOtherNationality(this)" required>
                                            <option selected value="Filipino">Filipino</option>
                                            <option value="American">American</option>
                                            <option value="Australian">Australian</option>
                                            <option value="Brazilian">Brazilian</option>
                                            <option value="British">British</option>
                                            <option value="Canadian">Canadian</option>
                                            <option value="Chinese">Chinese</option>
                                            <option value="Egyptian">Egyptian</option>
                                            <option value="French">French</option>
                                            <option value="German">German</option>
                                            <option value="Indian">Indian</option>
                                            <option value="Italian">Italian</option>
                                            <option value="Japanese">Japanese</option>
                                            <option value="Korean">Korean</option>
                                            <option value="Mexican">Mexican</option>
                                            <option value="Russian">Russian</option>
                                            <option value="Saudi Arabian">Saudi Arabian</option>
                                            <option value="Spanish">Spanish</option>
                                            <option value="other">Other Nationality</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please enter nationality.
                                        </div>
                                        <div id="otherNationalityContainer" style="display: none; margin-top: 10px;">
                                            <label for="otherNationality" class="form-label fw-bold">Specify Other Nationality:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="otherNationality" name="txtOtherNationality" placeholder="Enter Other Nationality">
                                                <div class="invalid-feedback">
                                                    Specify Nationality.
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="actualNationality" name="txtActualNationality">
                                    </div>

                                    <!-- Language Input -->
                                    <div class="col-md-4 mt-1 mb-1">
                                        <label for="language" class="form-label fw-bold mb-1">Language/s Spoken: <span class="required-asterisk">*</span></label>
                                        <select name="selLanguage" id="language" class="form-select form-control" onchange="toggleOtherLanguage(this)" required>
                                            <option selected value="Tagalog">Tagalog</option>
                                            <option value="English">English</option>
                                            <option value="Mandarin">Mandarin</option>
                                            <option value="Japanese">Japanese</option>
                                            <option value="Korean">Korean</option>
                                            <option value="Hindi">Hindi</option>
                                            <option value="Spanish">Spanish</option>
                                            <option value="Portuguese">Portuguese</option>
                                            <option value="German">German</option>
                                            <option value="French">French</option>
                                            <option value="Italian">Italian</option>
                                            <option value="Russian">Russian</option>
                                            <option value="Afrikaans">Afrikaans</option>
                                            <option value="Arabic">Arabic</option>
                                            <option value="other">Other Language</option>
                                        </select>
                                        <div id="otherLanguageContainer" style="display: none; margin-top: 10px;">
                                            <label for="otherLanguage" class="form-label fw-bold">Specify Other Language:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="otherLanguage" name="txtOtherLanguage" placeholder="Enter Other Language">
                                                <div class="invalid-feedback">
                                                    Specify Language.
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="actualLanguage" name="txtActualLanguage">
                                    </div>

                                    <!-- Religion Input -->
                                    <div class="col-md-4 mb-1">
                                        <label for="religion" class="form-label fw-bold">Religion: <span class="required-asterisk">*</span></label>
                                        <select name="selReligion" id="religion" class="form-select form-control" onchange="toggleOtherReligion(this)" required>
                                            <option disabled>Select Religion</option>
                                            <option value="Roman Catholic" selected>Roman Catholic</option>
                                            <option value="Protestant">Protestant</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Buddhism">Buddhism</option>
                                            <option value="Hinduism">Hinduism</option>
                                            <option value="Judaism">Judaism</option>
                                            <option value="Sikhism">Sikhism</option>
                                            <option value="Jainism">Jainism</option>
                                            <option value="Bahá'í">Bahá'í</option>
                                            <option value="Zoroastrianism">Zoroastrianism</option>
                                            <option value="Shinto">Shinto</option>
                                            <option value="Taoism">Taoism</option>
                                            <option value="other">Other Religion</option>
                                        </select>
                                        <div id="otherReligionContainer" style="display: none; margin-top: 10px;">
                                            <label for="otherReligion" class="form-label fw-bold">Specify Other Religion:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="otherReligion" name="txtOtherReligion" placeholder="Enter Other Religion">
                                                <div class="invalid-feedback">
                                                    Specify Religion.
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" id="actualReligion" name="txtActualReligion">
                                    </div>
                                </div>
                            
                                <div class="col-12">
                                    <div class="text-end">
                                        <button type="button" id="nextToFather" class="btn btn-primary ms-1">
                                            Next<i class="bi bi-arrow-right ms-1"></i> 
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                            <script>
                                // Function to toggle the other nationality fields
                                function toggleOtherNationality(selectElement) {
                                    const otherNationalityContainer = document.getElementById("otherNationalityContainer");
                                    const otherNationalityInput = document.getElementById("otherNationality");
                                    const actualNationalityInput = document.getElementById("actualNationality");

                                    if (selectElement.value === "other") {
                                        otherNationalityContainer.style.display = "block";
                                        otherNationalityInput.required = true;
                                    } else {
                                        otherNationalityContainer.style.display = "none";
                                        otherNationalityInput.required = false;
                                        actualNationalityInput.value = selectElement.value;
                                    }
                                }

                                document.getElementById("otherNationality").addEventListener("input", function() {
                                    document.getElementById("actualNationality").value = this.value;
                                });

                                document.getElementById("nationality").addEventListener("change", function() {
                                    if (this.value !== "other") {
                                        document.getElementById("actualNationality").value = this.value;
                                    }
                                });

                                function toggleOtherLanguage(selectElement) {
                                    const otherLanguageContainer = document.getElementById("otherLanguageContainer");
                                    const otherLanguageInput = document.getElementById("otherLanguage");
                                    const actualLanguageInput = document.getElementById("actualLanguage");

                                    if (selectElement.value === "other") {
                                        otherLanguageContainer.style.display = "block";
                                        otherLanguageInput.required = true;
                                    } else {
                                        otherLanguageContainer.style.display = "none";
                                        otherLanguageInput.required = false;
                                        actualLanguageInput.value = selectElement.value;
                                    }
                                }

                                document.getElementById("otherLanguage").addEventListener("input", function() {
                                    document.getElementById("actualLanguage").value = this.value;
                                });

                                document.getElementById("language").addEventListener("change", function() {
                                    if (this.value !== "other") {
                                        document.getElementById("actualLanguage").value = this.value;
                                    }
                                });

                                function toggleOtherReligion(selectElement) {
                                    const otherReligionContainer = document.getElementById("otherReligionContainer");
                                    const otherReligionInput = document.getElementById("otherReligion");
                                    const actualReligionInput = document.getElementById("actualReligion");

                                    if (selectElement.value === "other") {
                                        otherReligionContainer.style.display = "block";
                                        otherReligionInput.required = true;
                                    } else {
                                        otherReligionContainer.style.display = "none";
                                        otherReligionInput.required = false;
                                        actualReligionInput.value = selectElement.value;
                                    }
                                }

                                document.getElementById("otherReligion").addEventListener("input", function() {
                                    document.getElementById("actualReligion").value = this.value;
                                });

                                document.getElementById("religion").addEventListener("change", function() {
                                    if (this.value !== "other") {
                                        document.getElementById("actualReligion").value = this.value;
                                    }
                                });
                            </script>                      
                            <!-- Father's Information -->
                            <fieldset class="border p-4 rounded mb-4 d-none" id="fatherField">
                                <legend>Father's Information</legend>     
                                <legend class="sm">
                                    <input type="checkbox" name="fatherNoRecord" id="fatherNoRecord">
                                    <small><label for="fatherNoRecord">Mark if the student doesn't have a father's information.</label></small>
                                </legend>    
                                <div class="fatherInfo" id="fatherInfo">
                                    <!-- Father Inputs -->

                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label for="fatherlname" class="form-label fw-bold">Last Name: <span class="required-asterisk">*</span></label>
                                            <input type="text" class="form-control" id="fatherlname" name="txtFatherlname" placeholder="Father's Last Name"  required>
                                            <div class="invalid-feedback">
                                                Please enter the father's Last Name.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fatherfname" class="form-label fw-bold">First Name: <span class="required-asterisk">*</span></label>
                                            <input type="text" class="form-control" id="fatherfname" name="txtFatherfname" placeholder="Father's First Name"  required>
                                            <div class="invalid-feedback">
                                                Please enter the father's First Name.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fatherfname" class="form-label fw-bold">Middle Name: </label>
                                            <input type="text" class="form-control" id="fathermname" name="txtFathermname" placeholder="Father's Middle Name" >
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label for="fatherage" class="form-label fw-bold">Age: <span class="required-asterisk">*</span></label>
                                            <input type="number" name="txtFatherAge" id="fatherage" class="form-control" placeholder="Father's Age" required min="0" max="120">
                                            <input type="hidden" name="txtFatherYear" id="txtFatherYear" class="form-control">
                                            <div class="invalid-feedback">
                                                Please enter a valid age.
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const ageInput = document.getElementById('fatherage');
                                                const birthYearInput = document.getElementById('txtFatherYear');

                                                function updateBirthYear() {
                                                    const currentYear = new Date().getFullYear();
                                                    const age = parseInt(ageInput.value, 10);

                                                    if (!isNaN(age) && age >= 0) {
                                                        const birthYear = currentYear - age;
                                                        birthYearInput.value = birthYear;
                                                    } else {
                                                        birthYearInput.value = ''; // Clear the birth year if age is invalid
                                                    }
                                                }
                                                // Add event listener to the age input to update birth year on input change
                                                ageInput.addEventListener('input', updateBirthYear);
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
                                                required
                                                oninput="validateContactNumber(this)">
                                                <div class="invalid-feedback">
                                                    Please enter a valid father's contact number.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fathereducatt" class="form-label fw-bold">Educational Attainment: <span class="required-asterisk">*</span></label>
                                            <select class="form-select form-control" name="txtFatherEducAtt" id="fathereducatt" required>
                                            <option selected disabled value="">Select Education</option>
                                            <option value="Elementary Level">Elementary Level</option>
                                            <option value="Elementary Graduate">Elementary Graduate</option>
                                            <option value="High School Level">High School Level</option>
                                            <option value="High School Graduate">High School Graduate</option>
                                            <option value="Vocational/Technical School">Vocational/Technical School</option>
                                            <option value="Associate's Degree">Associate's Degree</option>
                                            <option value="Bachelor's Degree">Bachelor's Degree</option>
                                            <option value="Master's Degree">Master's Degree</option>
                                            <option value="Doctorate/Ph.D.">Doctorate/Ph.D.</option>
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

                                                if (value.length > 0 && value[0] === '0') {
                                                    value = value.slice(1);
                                                }

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
                                            <input type="text" name="txtFatherOccupation" id="fatheroccupation" class="form-control" placeholder="Father's Occupation">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fatheroffice" class="form-label fw-bold">Office/Business Address:</label>
                                            <input type="text" class="form-control" id="fatheroffice" name="txtFatherOffice" placeholder="(if any)">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fatheremail" class="form-label fw-bold">Email:</label>
                                            <input type="email" class="form-control" id="fatheremail" name="txtFatherEmail" placeholder="e.g., juandelacruz@gmail.com"  >
                                            <div class="invalid-feedback">
                                                Please enter the father's email address.
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Checkbox for same address -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <label for="completeAddress" class="form-label fw-bold">Complete Address: <span class="required-asterisk">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="sameAddressCheckbox" name="sameAddressCheckbox">
                                                <label class="form-check-label" for="sameAddressCheckbox">
                                                    Same as Student's Address
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fathers address -->
                                    <div class="fatherAdress" id="fatherAdress">
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <input type="text" class="form-control" id="fatherHouseNumber" name="txtfHouseNumber" placeholder="House No.">
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
                    
                                <div class="col-12">
                                    <div class="text-end">
                                        <button type="button" class="btn btn-secondary" id="backToStudent">
                                            <i class="bi bi-arrow-left"></i>
                                            Back
                                        </button>
                                        <button type="button" id="nextToMother" class="btn btn-primary ms-1">
                                            Next<i class="bi bi-arrow-right ms-1"></i> 
                                        </button>
                                    </div>
                                </div>
                            </fieldset>
                            <!-- mother's Information -->
                            <fieldset class="border p-4 rounded mb-4 d-none" id="motherField">
                                <legend>Mother's Information</legend>     
                                <legend class="sm"><input type="checkbox" name="motherNoRecord" id="motherNoRecord">
                                    <small><label for="motherNoRecord">Mark if the student doesn't have a mother's information.</label></small>
                                </legend>    
                                <div class="motherInfo" id="motherInfo">
                                    <!-- Mother Inputs -->
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label for="motherlname" class="form-label fw-bold">Last Name: <span class="required-asterisk">*</span></label>
                                            <input type="text" class="form-control" id="motherlname" name="txtMotherlname" placeholder="Mother's Last Name" required>
                                            <div class="invalid-feedback">
                                                Please enter the mother's Last Name.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="motherfname" class="form-label fw-bold">First Name: <span class="required-asterisk">*</span></label>
                                            <input type="text" class="form-control" id="motherfname" name="txtMotherfname" placeholder="Mother's First Name" required>
                                            <div class="invalid-feedback">
                                                Please enter the mother's First Name.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="mothermname" class="form-label fw-bold">Middle Name: </label>
                                            <input type="text" class="form-control" id="mothermname" name="txtMothermname" placeholder="Mother's Middle Name" >
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label for="motherage" class="form-label fw-bold">Age: <span class="required-asterisk">*</span></label>
                                            <input type="number" name="txtMotherAge" id="motherage" class="form-control" placeholder="Mother's Age" required min="0" max="120">
                                            <input type="hidden" name="txtMotherYear" id="txtMotherYear">
                                            <div class="invalid-feedback">
                                                Please enter a valid age.
                                            </div>
                                        </div>

                                        <script>
                                            // Get the age input element for father and mother
                                            let fatherAgeInput = document.getElementById('fatherage');
                                            let motherAgeInput = document.getElementById('motherage');

                                            // Set the current year and maximum age
                                            let currentYear = new Date().getFullYear();
                                            let maxAge = 120;

                                            // Add input event listener for father age validation
                                            fatherAgeInput.oninput = function() {
                                                if (fatherAgeInput.value < 0) {
                                                    fatherAgeInput.setCustomValidity("Age cannot be negative.");
                                                    fatherAgeInput.classList.add('is-invalid');
                                                } else if (fatherAgeInput.value > maxAge) {
                                                    fatherAgeInput.setCustomValidity(`Age cannot be more than ${maxAge}.`);
                                                    fatherAgeInput.classList.add('is-invalid'); 
                                                } else {
                                                    fatherAgeInput.setCustomValidity(""); 
                                                    fatherAgeInput.classList.remove('is-invalid'); 
                                                }
                                            };

                                            // Add input event listener for mother age validation
                                            motherAgeInput.oninput = function() {
                                                if (motherAgeInput.value < 0) {
                                                    motherAgeInput.setCustomValidity("Age cannot be negative.");
                                                    motherAgeInput.classList.add('is-invalid'); 
                                                } else if (motherAgeInput.value > maxAge) {
                                                    motherAgeInput.setCustomValidity(`Age cannot be more than ${maxAge}.`);
                                                    motherAgeInput.classList.add('is-invalid'); 
                                                } else {
                                                    motherAgeInput.setCustomValidity(""); 
                                                    motherAgeInput.classList.remove('is-invalid'); 
                                                }
                                            };

                                            // Set the maximum age to 120 for both father and mother
                                            fatherAgeInput.setAttribute("max", maxAge);
                                            motherAgeInput.setAttribute("max", maxAge);
                                        </script>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const ageInput = document.getElementById('motherage');
                                                const birthYearInput = document.getElementById('txtMotherYear');

                                                function updateBirthYear() {
                                                    const currentYear = new Date().getFullYear();
                                                    const age = parseInt(ageInput.value, 10);

                                                    if (!isNaN(age) && age >= 0) {
                                                        const birthYear = currentYear - age;
                                                        birthYearInput.value = birthYear;
                                                    } else {
                                                        birthYearInput.value = ''; 
                                                    }
                                                }

                                                // Add event listener to the age input to update birth year on input change
                                                ageInput.addEventListener('input', updateBirthYear);
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
                                                required
                                                oninput="validateContactNumber(this)">
                                                <div class="invalid-feedback">
                                                    Please enter a valid mother's contact number.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="mothereducatt" class="form-label fw-bold">Educational Attainment: <span class="required-asterisk">*</span></label>
                                            <select class="form-select form-control" name="txtMotherEducAtt" id="mothereducatt" required>
                                            <option value="" selected disabled>Select Education</option>
                                            <option value="Elementary Level">Elementary Level</option>
                                            <option value="Elementary Graduate">Elementary Graduate</option>
                                            <option value="High School Level">High School Level</option>
                                            <option value="High School Graduate">High School Graduate</option>
                                            <option value="Vocational/Technical School">Vocational/Technical School</option>
                                            <option value="Associate's Degree">Associate's Degree</option>
                                            <option value="Bachelor's Degree">Bachelor's Degree</option>
                                            <option value="Master's Degree">Master's Degree</option>
                                            <option value="Doctorate/Ph.D.">Doctorate/Ph.D.</option>
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

                                                if (value.length > 0 && value[0] === '0') {
                                                    value = value.slice(1);
                                                }

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
                                            <input type="text" name="txtMotherOccupation" id="motheroccupation" class="form-control" placeholder="Mother's Occupation">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="motheroffice" class="form-label fw-bold">Office/Business Address:</label>
                                            <input type="text" class="form-control" id="motheroffice" name="txtMotherOffice" placeholder="(if any)">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="motheremail" class="form-label fw-bold">Email:</label>
                                            <input type="email" class="form-control" id="motheremail" name="txtMotherEmail" placeholder="e.g., juandelacruz@gmail.com"  >
                                            <div class="invalid-feedback">
                                                Please enter the mother's email address.
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Checkbox for same address -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <label for="motherHouseNumber" class="form-label fw-bold">Complete Address: <span class="required-asterisk">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="sameAddressCheckboxM" name="sameAddressCheckboxM">
                                                <label class="form-check-label" for="sameAddressCheckboxM">
                                                    Same as Student's Address
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Mothers address -->
                                    <div class="motherAdress" id="motherAdress">
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <input type="text" class="form-control" id="motherHouseNumber" name="txtmHouseNumber" placeholder="House No." >
                                                <div class="invalid-feedback">
                                                    Please enter the house number.
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="text" class="form-control" id="motherStreetName" name="txtmStreetName" placeholder="Street Name" >
                                                <div class="invalid-feedback">
                                                    Please enter the street name.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <input type="text" class="form-control" id="motherSubdivision" name="txtmSubdivision" placeholder="Subdivision/Village" >
                                                <div class="invalid-feedback">
                                                    Please enter the subdivision.
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                            <select class="form-select form-control selectpicker" id="motherRegion" data-live-search="true" name="txtmRegion" >
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
                                            <select class="form-select form-control selectpicker" id="motherProvince" data-live-search="true" >
                                                <option value="" selected disabled>Select Province</option>
                                            </select>
                                            <input type="hidden" id="selectedProvinceDescM" name="txtmProvince">
                                                <div class="invalid-feedback">
                                                    Please select a province.
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                            <select class="form-select form-control selectpicker" id="motherCity" data-live-search="true" >
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
                                                <select class="form-select form-control selectpicker" id="motherBarangay" data-live-search="true" >
                                                    <option value="" selected disabled>Select Barangay</option>
                                                </select>
                                                <input type="hidden" id="selectedBarangayDescM" name="txtmBarangay">
                                                <div class="invalid-feedback">
                                                    Please select barangay.
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <input type="number" class="form-control" id="motherPostalCode" name="txtmPostalCode" placeholder="Postal Code" >
                                                <div class="invalid-feedback">
                                                    Please enter the Postal code.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="col-12">
                                    <div class="text-end">
                                        <button type="button" class="btn btn-secondary" id="backToFather">
                                            <i class="bi bi-arrow-left"></i>
                                            Back
                                        </button>
                                        <button type="button" id="nextToGuardian" class="btn btn-primary ms-1">
                                            Next<i class="bi bi-arrow-right ms-1"></i> 
                                        </button>
                                    </div>
                                </div>

                            </fieldset>

                            <!-- guardian's Information -->
                            <fieldset class="border p-4 rounded mb-4 d-none" id="gdField">
                            <legend>Guardian's Information</legend>     

                            <div class="row mb-4">
                                <div class="col-md-6">
                                <label for="selGuardian" class="form-label fw-bold">Select Guardian: <span class="required-asterisk">*</span></label>
                                    <select class="form-control form-select" name="selGuardian" id="selGuardian" required>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a guardian.
                                    </div>
                                </div>
                            </div>
                            <div id="gdInfo" class="gdInfo" style="display: none;">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="otherGuardian" class="form-label fw-bold">Other Guardian: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="otherGuardian" name="otherGuardian" placeholder="Specify the Relationship" >
                                        <div class="invalid-feedback">
                                            Please specify Relationship.
                                        </div>
                                    </div>
                                    <div id="gdcontactnum" class="col-md-6" style="display: block;">
                                    <label for="gdcontactnumber" class="form-label fw-bold">Contact Number: <span class="required-asterisk">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">(+63)</span>
                                            <input type="text" 
                                            class="form-control" 
                                            id="gdcontactnumber" 
                                            name="txtGdContactNum" 
                                            pattern="\d{10}" 
                                            placeholder="Enter the last 10-digits" 
                                            oninput="validateContactNumber(this)"
                                            >
                                            <div class="invalid-feedback">
                                                Please enter a valid guardian's contact number.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const contactInput = document.getElementById('gdcontactnumber');

                                        contactInput.addEventListener('input', function () {
                                            let value = contactInput.value.replace(/\D/g, '');

                                            if (value.length > 0 && value[0] === '0') {
                                                value = value.slice(1);
                                            }

                                            if (value.length > 10) {
                                                value = value.slice(0, 10); 
                                            }

                                            contactInput.value = value;
                                        });

                                    });
                                </script>
                                <!-- Guardian Inputs -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                    <label for="gdlname" class="form-label fw-bold">Last Name: <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control" id="gdlname" name="txtGdlname" placeholder="Guardian's Last Name" >
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
                                    <label for="gdmname" class="form-label fw-bold mt-1 mb-2">Middle Name: </label>
                                        <input type="text" class="form-control" id="gdmname" name="txtGdmname" placeholder="Guardian's Middle Name" >
                                    </div>
                                </div>
                            </div>

                            

                            <div class="col-12">
                                <div class="text-end">
                                    <button type="button" class="btn btn-secondary" id="backToMother">
                                        <i class="bi bi-arrow-left"></i>
                                        Back
                                    </button>
                                    <button type="submit" class="btn btn-primary ms-1" name="saveStudentBtn">
                                        <i class="bi bi-save"></i> Save
                                    </button>
                                </div>
                            </div>


                            </fieldset>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selGuardian = document.getElementById('selGuardian');
        const fatherNoRecord = document.getElementById('fatherNoRecord');
        const motherNoRecord = document.getElementById('motherNoRecord');
        const gdInfo = document.getElementById('gdInfo');

        function updateGuardianOptions() {
            // Clear existing options except the placeholder
            selGuardian.innerHTML = '<option value="" disabled selected>Select a Guardian:</option>';

            // Check if checkboxes are checked
            const fatherChecked = fatherNoRecord.checked;
            const motherChecked = motherNoRecord.checked;

            // Populate options based on checkbox states
            if (!fatherChecked) {
                const fatherOption = document.createElement('option');
                fatherOption.value = 'Father';
                fatherOption.textContent = 'Father';
                selGuardian.appendChild(fatherOption);
            }
            if (!motherChecked) {
                const motherOption = document.createElement('option');
                motherOption.value = 'Mother';
                motherOption.textContent = 'Mother';
                selGuardian.appendChild(motherOption);
            }

            // Add "Others" option to the end
            const othersOption = document.createElement('option');
            othersOption.value = 'Others';
            othersOption.textContent = 'Others';
            selGuardian.appendChild(othersOption);

            // Set the default selected option
            if (fatherChecked && motherChecked) {
                // Both are checked, select "Others"
                selGuardian.value = 'Others';
            } else if (!fatherChecked && !motherChecked) {
                // Both are not checked, no selection
                selGuardian.value = '';
            } else {
                // Only one is checked, select the one that is not checked
                if (fatherChecked) {
                    selGuardian.value = 'Mother';
                } else if (motherChecked) {
                    selGuardian.value = 'Father';
                }
            }
        }

        function toggleGdInfo() {
            if (selGuardian.value === 'Others') {
                gdInfo.style.display = 'block';
                // Set required attribute for all fields except middle name
                document.getElementById('otherGuardian').setAttribute('required', 'required');
                document.getElementById('gdcontactnumber').setAttribute('required', 'required');
                document.getElementById('gdlname').setAttribute('required', 'required');
                document.getElementById('gdfname').setAttribute('required', 'required');
                // Middle name is not required
            } else {
                gdInfo.style.display = 'none';
                // Remove required attribute from all fields
                document.getElementById('otherGuardian').removeAttribute('required');
                document.getElementById('gdcontactnumber').removeAttribute('required');
                document.getElementById('gdlname').removeAttribute('required');
                document.getElementById('gdfname').removeAttribute('required');
                // Middle name remains as is
            }
        }

        // Initial call to set options
        updateGuardianOptions();

        // Add event listeners to checkboxes and select element
        fatherNoRecord.addEventListener('change', function () {
            updateGuardianOptions();
            toggleGdInfo();
        });

        motherNoRecord.addEventListener('change', function () {
            updateGuardianOptions();
            toggleGdInfo();
        });

        selGuardian.addEventListener('change', toggleGdInfo);
    });
</script>



<script>
document.getElementById('sameAddressCheckbox').addEventListener('change', function() {
    const isChecked = this.checked;

    // Select the div containing the father's address fields
    const fatherAdressDiv = document.getElementById('fatherAdress');

    // Select specific address fields
    const fatherStreetName = document.getElementById('fatherStreetName');
    const fatherSubdivision = document.getElementById('fatherSubdivision');
    const fatherRegion = document.getElementById('fatherRegion');
    const fatherProvince = document.getElementById('fatherProvince');
    const fatherCity = document.getElementById('fatherCity');
    const fatherBarangay = document.getElementById('fatherBarangay');
    const fatherPostalCode = document.getElementById('fatherPostalCode');

    // Array of specific fields to toggle 'required' attribute
    const fields = [fatherStreetName, fatherSubdivision, fatherRegion, fatherProvince, fatherCity, fatherBarangay, fatherPostalCode];

    if (isChecked) {
        // Hide the address fields
        fatherAdressDiv.style.display = 'none';

        // Remove 'required' attribute from specific fields
        fields.forEach(field => {
            field.removeAttribute('required');
        });
    } else {
        // Show the address fields
        fatherAdressDiv.style.display = 'block';

        // Add 'required' attribute to specific fields
        fields.forEach(field => {
            field.setAttribute('required', 'required');
        });
    }
});

</script>
<script>
document.getElementById('sameAddressCheckboxM').addEventListener('change', function() {
    const isChecked = this.checked;

    // Select the div containing the mother's address fields
    const motherAdressDiv = document.getElementById('motherAdress');

    // Select specific address fields
    const motherStreetName = document.getElementById('motherStreetName');
    const motherSubdivision = document.getElementById('motherSubdivision');
    const motherRegion = document.getElementById('motherRegion');
    const motherProvince = document.getElementById('motherProvince');
    const motherCity = document.getElementById('motherCity');
    const motherBarangay = document.getElementById('motherBarangay');
    const motherPostalCode = document.getElementById('motherPostalCode');

    // Array of specific fields to toggle 'required' attribute
    const fields = [motherStreetName, motherSubdivision, motherRegion, motherProvince, motherCity, motherBarangay, motherPostalCode];

    if (isChecked) {
        // Hide the address fields
        motherAdressDiv.style.display = 'none';

        // Remove 'required' attribute from specific fields
        fields.forEach(field => {
            field.removeAttribute('required');
        });
    } else {
        // Show the address fields
        motherAdressDiv.style.display = 'block';

        // Add 'required' attribute to specific fields
        fields.forEach(field => {
            field.setAttribute('required', 'required');
        });
    }
});

</script>

                            



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fatherCheckbox = document.getElementById('fatherNoRecord');
        const motherCheckbox = document.getElementById('motherNoRecord');
        const fatherInfoDiv = document.getElementById('fatherInfo');
        const motherInfoDiv = document.getElementById('motherInfo');
        const gdInfoDiv = document.getElementById('gdInfo');
        const gdContactNum = document.getElementById('gdcontactnum');

        function toggleInfo(div, checkbox) {
            if (checkbox.checked) {
                // Hide the div and disable its inputs
                div.classList.add('hidden');
                div.querySelectorAll('input, select').forEach(element => {
                    element.disabled = true;
                    if (element.hasAttribute('required')) {
                        element.setAttribute('data-original-required', true); // Store original required state
                        element.removeAttribute('required'); // Remove required attribute
                    }
                });
            } else {
                // Show the div and enable its inputs
                div.classList.remove('hidden');
                div.querySelectorAll('input, select').forEach(element => {
                    element.disabled = false;
                    if (element.hasAttribute('data-original-required')) {
                        element.setAttribute('required', true); // Restore required attribute
                        element.removeAttribute('data-original-required'); // Clean up the data-original-required attribute
                    }
                });
            }
        }

        function updateGdInfoVisibility() {
            if (fatherCheckbox.checked && motherCheckbox.checked) {
                gdInfoDiv.style.display = 'block'; // Show gdInfo when both checkboxes are checked
                gdContactNum.style.display = 'block'; 
            } else {
                gdInfoDiv.style.display = 'none'; // Hide gdInfo otherwise
                gdContactNum.style.display = 'none'; // Hide contact number if gdInfo is hidden
            }
        }

        fatherCheckbox.addEventListener('change', function() {
            toggleInfo(fatherInfoDiv, fatherCheckbox);
            updateGdInfoVisibility(); // Update gdInfo visibility after toggling fatherInfo
        });

        motherCheckbox.addEventListener('change', function() {
            toggleInfo(motherInfoDiv, motherCheckbox);
            updateGdInfoVisibility(); // Update gdInfo visibility after toggling motherInfo
        });
    });
</script>

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
    document.addEventListener('DOMContentLoaded', function () {
        // Get all fieldsets
        const fieldsets = {
            studentField: document.getElementById('studentField'),
            fatherField: document.getElementById('fatherField'),
            motherField: document.getElementById('motherField'),
            gdField: document.getElementById('gdField')
        };

        // Next buttons
        document.getElementById('nextToFather').addEventListener('click', function () {
            toggleFieldset('studentField', 'fatherField');
        });

        document.getElementById('nextToMother').addEventListener('click', function () {
            toggleFieldset('fatherField', 'motherField');
        });

        document.getElementById('nextToGuardian').addEventListener('click', function () {
            toggleFieldset('motherField', 'gdField');
        });

        // Back buttons
        document.getElementById('backToStudent').addEventListener('click', function () {
            toggleFieldset('fatherField', 'studentField');
        });

        document.getElementById('backToFather').addEventListener('click', function () {
            toggleFieldset('motherField', 'fatherField');
        });

        document.getElementById('backToMother').addEventListener('click', function () {
            toggleFieldset('gdField', 'motherField');
        });

        // Toggle function
        function toggleFieldset(hideFieldset, showFieldset) {
            fieldsets[hideFieldset].classList.add('d-none');
            fieldsets[showFieldset].classList.remove('d-none');
        }

        // Validation function
        function validateFieldset(fieldset) {
            let isValid = true;
            const requiredFields = fieldset.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid'); // Bootstrap class for invalid input
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            return isValid;
        }
    });
</script>
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
    // this is to auto generate the age
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
<script>
    function validateContactNumber(input) {
        // Remove non-numeric characters and enforce length limit
        input.value = input.value.replace(/[^0-9]/g, '').slice(0, 10);

        // Check if first digit is 9; if not, clear input
        if (input.value.length > 0 && input.value[0] !== '9') {
            input.setCustomValidity("Contact number must start with 9.");
            input.reportValidity();
        } else {
            input.setCustomValidity("");
        }
    }
</script>

<script>
    const fatherCheckbox = document.getElementById('sameAddressCheckbox');
    const motherCheckbox = document.getElementById('sameAddressCheckboxM');

    const fatherFields = document.querySelectorAll('#fatherHouseNumber, #fatherStreetName, #fatherSubdivision, #fatherRegion, #fatherProvince, #fatherCity, #fatherBarangay, #fatherPostalCode');
    const motherFields = document.querySelectorAll('#motherHouseNumber, #motherStreetName, #motherSubdivision, #motherRegion, #motherProvince, #motherCity, #motherBarangay, #motherPostalCode');

    // Function to toggle 'required' attribute for a set of fields based on a checkbox
    function toggleRequiredFields(checkbox, fields) {
        fields.forEach(field => {
            if (checkbox.checked) {
                field.removeAttribute('required');
            } else {
                field.setAttribute('required', 'required');
            }
        });
    }

    // Add event listeners for both checkboxes
    fatherCheckbox.addEventListener('change', () => toggleRequiredFields(fatherCheckbox, fatherFields));
    motherCheckbox.addEventListener('change', () => toggleRequiredFields(motherCheckbox, motherFields));

    // Initialize required attributes on page load
    document.addEventListener('DOMContentLoaded', () => {
        toggleRequiredFields(fatherCheckbox, fatherFields);
        toggleRequiredFields(motherCheckbox, motherFields);
    });
</script>


</body>

</html>
