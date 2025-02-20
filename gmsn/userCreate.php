<?php
    include 'session.php';

    require_once("includes/config.php");
    $userID = isset($_GET['userID']) ? intval($_GET['userID']) : 0;
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE uid = :uid");
    $stmt->bindParam(':uid', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $usercol = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>User Registration</title>
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

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <style>

        .custom-container {
            margin-left: -10px;
            margin-right: -15px;
            margin-top: -60px;
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
            margin-left: 8px;
        }

        .form-section {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            font-size: 14px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .form-control-file {
            font-size: 14px;
        }

        .btn-primary {
            font-size: 14px;
            padding: 8px 20px;
        }

        .text-end {
            text-align: end;
        }
        #imagePreviewContainer {
    width: 200px; 
    height: 200px; 
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden; 
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f0f0f0; 
}

#imagePreview {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
}

    </style>
<style>
    .form-check-input {
        width: 40px;
        height: 20px;
    }

    .form-check-input:checked {
        background-color: #1a237e;
    }

    .form-check-label {
        font-weight: bold;
        padding-left: 10px;
        color: #1a237e; 
    }

    .form-check-input:not(:checked) + .form-check-label {
        color: #d9534f;
    }
</style>
</head>

<body>

    <?php if ($_SESSION['userTypeID'] == 1) {
        require_once "support/header.php";
        require_once "support/sidebar.php";
    }else{
        require_once "../faculty/support/header.php";
        require_once "../faculty/support/sidebar.php";
    } ?>

    <main id="main" class="main">
        <section class="section">
            <div class="custom-container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" aria-current="page"><a href="users.php">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User Registration</li>
                    </ol>
                </nav>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row"></div>
                                <form id="facultyForm" action="save_faculty.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    <fieldset class="border p-4 rounded mb-4">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="row me-2">
                                                    <div class="col-md-11 d-flex align-items-center">
                                                        <legend>Create User Account</legend>
                                                    </div>
                                                </div>
                                                <div class="row mb-2 mt-2">
                                                    <div class="col-md-5 mb-3">
                                                        <label for="selType" class="form-label fw-bold">User Type: <span style="color: red; font-size: 18px">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                                            </div>
                                                            <select class="form-select form-control" id="selType" name="selType" required>
                                                                <option selected disabled value="">Select User Type</option>
                                                                <?php
                                                                $userTypeIDURL = $_GET['userTypeID']; 
                                                                try {
                                                                    $query = "SELECT * FROM user_type";
                                                                    $stmt = $conn->query($query);
                                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                                        $selected = ($row['typeID'] == $userTypeIDURL) ? 'selected' : '';
                                                                        echo '<option value="' . $row['typeID'] . '" ' . $selected . '>' . $row['userType'] . '</option>';
                                                                    }
                                                                } catch (PDOException $e) {
                                                                    echo '<option disabled>Error fetching User Types</option>';
                                                                }
                                                                ?>
                                                            </select>

                                                            <div class="invalid-feedback">Please select a user type.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="userID" class="form-label fw-bold">User ID: <span style="color: red; font-size: 18px">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control me-3" id="userID" name="txtuserID" placeholder="User ID" required>
                                                            <div class="invalid-feedback">Please provide a user ID.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 me-0">
                                                        <label for="txtuserlname" class="form-label fw-bold">Last Name: <span style="color: red; font-size: 18px">*</span></label>
                                                            <input type="text" class="form-control" id="txtuserlname" name="txtuserlname" placeholder="eg. Dela Cruz" required>
                                                            <div class="invalid-feedback">Please provide a last name.</div>
                                                    </div>
                                                    <div class="col-md-4 ms-0">
                                                        <label for="txtuserfname" class="form-label fw-bold">First Name: <span style="color: red; font-size: 18px">*</span></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="txtuserfname" name="txtuserfname" placeholder="eg. Juan" required>
                                                            <div class="invalid-feedback">Please provide a last name.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 ms-0 mt-1">
                                                        <label for="txtusermname" class="form-label fw-bold">Middle Name: </label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="txtusermname" name="txtusermname" placeholder="(Optional)">
                                                            <div class="invalid-feedback">Please provide a last name.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Image Preview -->
                                            <div class="col-md-2 d-flex flex-column align-items-end mt-2">
                                                <div class="position-relative mb-2">
                                                    <div id="imagePreviewContainer">
                                                        <img id="imagePreview" src="assets/img/user.png" alt="Preview">
                                                    </div>
                                                </div>
                                                <div class="input-group" style="border: 1px solid #ccc; border-radius: 5px; overflow: hidden; width: 200px;">
                                                    <input type="file" id="photo" name="userPhoto" class="form-control form-control-sm" accept="image/*" onchange="previewImage(event)" style="border: none;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-md-4 mb-3">
                                                <label for="selGender" class="form-label fw-bold">Gender: <span style="color: red; font-size: 18px">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                                    </div>
                                                    <select class="form-select form-control" id="selGender" name="selGender" required>
                                                    <option selected disabled value="">Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please select a gender.</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="txtcontact" class="form-label fw-bold">Contact No.: <span style="color: red; font-size: 18px">*</span></label>
                                                <div class="input-group">
                                                        <span class="input-group-text">+63</span>
                                                    <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    name="txtcontact" 
                                                    id="txtcontact" 
                                                    placeholder="Enter the Last 10-digits" 
                                                    maxlength="10" 
                                                    required
                                                    oninput="validateContactNumber(this)">                                                    
                                                    <div class="invalid-feedback">Please provide a valid contact number.</div>
                                                </div>
                                            </div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    const contactInput = document.getElementById('txtcontact');

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
                                            <div class="col-md-4 mb-3">
                                                <label for="txtemail" class="form-label fw-bold">Email: <span style="color: red; font-size: 18px">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                    </div>
                                                    <input type="email" class="form-control" id="txtemail" name="txtemail" placeholder="email@example.com" required>
                                                    <div class="invalid-feedback">Please provide an email address.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="userPassword" class="form-label fw-bold">Password: <span style="color: red; font-size: 18px">*</span></label> 
                                                    <small><input type="checkbox" name="defaultPass" id="defaultPass"> 
                                                        <label for="defaultPass">Use Contact No. as default Password</label>
                                                    </small>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                    </div>
                                                    <input type="password" 
                                                            class="form-control" id="userPassword" 
                                                            name="userPassword" 
                                                            placeholder="Password" 
                                                            oninput="validatePasswordRequirement(this)"
                                                            required>
                                                    <button class="btn btn-outline-secondary" type="button" id="passwordToggle">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                                <small id="passwordHelp" class="form-text text-muted">
                                                        Password must be at least 12 characters long, contain at least one uppercase letter, and at least one symbol.
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="userConPassword" class="form-label fw-bold">Confirm Password: <span style="color: red; font-size: 18px">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                    </div>
                                                    <input type="password" 
                                                            class="form-control" 
                                                            id="userConPassword" 
                                                            name="userConPassword" 
                                                            placeholder="Confirm Password"
                                                            oninput="validatePasswordRequirement(this)" 
                                                            required>
                                                    <button class="btn btn-outline-secondary" type="button" id="conPasswordToggle">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small id="userPasswordHelp" class="form-text mb-3"></small>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="userStatus" class="form-label fw-bold">Initial Status:</label>
                                                    <div class="form-check form-switch" style="margin-left: 20px;"> 
                                                        <input class="form-check-input mt-0 mb-1" type="checkbox" id="userStatus">
                                                        <label class="form-check-label" for="userStatus" id="statusLabel">Inactive</label>
                                                        <input type="hidden" id="hiddenStatus" name="status" value="0"> <!-- Set initial hidden value to 0 (Inactive) -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </fieldset>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="submit" class="btn btn-primary me-md-2" name="saveUserBtn">
                                            <i class="bi bi-person-plus"></i> Register
                                        </button>
                                        <button type="reset" class="btn btn-secondary me-md-2">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        Clear
                                        </button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php require_once "support/footer.php" ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const userStatus = document.getElementById('userStatus');
        const hiddenStatus = document.getElementById('hiddenStatus');
        const statusLabel = document.getElementById('statusLabel');

        hiddenStatus.value = userStatus.checked ? '1' : '0';

        statusLabel.textContent = userStatus.checked ? 'Active' : 'Inactive';
        statusLabel.style.color = userStatus.checked ? '#1a237e' : '#d9534f'; 

        userStatus.addEventListener('change', function () {
            hiddenStatus.value = userStatus.checked ? '1' : '0';
            
            if (userStatus.checked) {
                statusLabel.textContent = 'Active';
                statusLabel.style.color = '#1a237e'; 
            } else {
                statusLabel.textContent = 'Inactive';
                statusLabel.style.color = '#d9534f'; 
            }
        });
    });
</script>

<script>
    // Preview Image Function
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

    // Contact Number Validation Function
    function validateContactNumber(input) {
        // Remove non-numeric characters
        input.value = input.value.replace(/[^0-9]/g, '');

        // Check if the input length is exactly 10 and starts with '9'
        if (input.value[0] !== '9') {
            input.setCustomValidity("Contact number must start with 9.");
        } else if (input.value.length !== 10) {
            input.setCustomValidity("Contact number must be exactly 10 digits."); 
        } else {
            input.setCustomValidity("");
        }

        input.reportValidity();
    }

    function validatePasswordRequirement(input) {
        const password = input.value;
        const passwordHelp = document.getElementById('passwordHelp');

        // Password requirements:
        const minLength = password.length >= 12;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password); 

        // Set custom validity based on conditions
        if (!minLength) {
            input.setCustomValidity("Password must be at least 12 characters long.");
        } else if (!hasUpperCase) {
            input.setCustomValidity("Password must contain at least one uppercase letter.");
        } else if (!hasSymbol) {
            input.setCustomValidity("Password must contain at least one symbol.");
        } else {
            input.setCustomValidity(""); 
        }

        input.reportValidity();

        // Update the help text dynamically
        if (!minLength) {
            passwordHelp.textContent = "Password must be at least 12 characters long.";
        } else if (!hasUpperCase) {
            passwordHelp.textContent = "Password must contain at least one uppercase letter.";
        } else if (!hasSymbol) {
            passwordHelp.textContent = "Password must contain at least one symbol.";
        } else {
            passwordHelp.textContent = "";
        }

        // Update the input field's style
        if (!minLength || !hasUpperCase || !hasSymbol) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid'); 
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid'); 
        }
    }



    // Password Validation and Form Validation
    (function () {
        'use strict';

        const forms = document.querySelectorAll('.needs-validation');
        const passwordInput = document.getElementById('userPassword');
        const conPasswordInput = document.getElementById('userConPassword');
        const passwordToggle = document.getElementById('passwordToggle');
        const conPasswordToggle = document.getElementById('conPasswordToggle');

        // Function to validate password length
        function validatePassword(input) {
            const minLength = input.value.length >= 12;
            if (!minLength) {
                input.setCustomValidity('Password must be at least 12 characters long.');
            } else {
                input.setCustomValidity(''); 
            }
        }

        // Function to validate password confirmation
        function validateConPassword(password, conPassword) {
            if (conPassword.value !== password.value) {
                conPassword.setCustomValidity('Passwords do not match.');
            } else {
                conPassword.setCustomValidity(''); 
            }
        }

        // Add validation to forms
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                validatePassword(passwordInput); 
                validateConPassword(passwordInput, conPasswordInput); 

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });

        passwordInput.addEventListener('input', () => {
            validatePassword(passwordInput);
            validateConPassword(passwordInput, conPasswordInput); 
        });

        conPasswordInput.addEventListener('input', () => {
            validateConPassword(passwordInput, conPasswordInput); 
        });

        // Toggle password visibility
        if (passwordToggle && conPasswordToggle) {
            passwordToggle.addEventListener('click', () => {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                
                // Change icon based on the current type
                passwordToggle.innerHTML = type === 'password' 
                    ? '<i class="bi bi-eye"></i>' 
                    : '<i class="bi bi-eye-slash"></i>'; 
            });

            conPasswordToggle.addEventListener('click', () => {
                const type = conPasswordInput.type === 'password' ? 'text' : 'password';
                conPasswordInput.type = type;
                
                // Change icon based on the current type
                conPasswordToggle.innerHTML = type === 'password' 
                    ? '<i class="bi bi-eye"></i>' 
                    : '<i class="bi bi-eye-slash"></i>'; 
            });
        }

    })();

    document.getElementById('defaultPass').addEventListener('change', function() {
        const passwordInput = document.getElementById('userPassword');
        const conPasswordInput = document.getElementById('userConPassword');
        const contactNumber = document.getElementById('txtcontact').value;
        const passwordHelp = document.getElementById('passwordHelp'); 

        // Toggle readonly and required attributes
        passwordInput.toggleAttribute('readonly', this.checked);
        passwordInput.toggleAttribute('required', !this.checked);

        conPasswordInput.toggleAttribute('readonly', this.checked);
        conPasswordInput.toggleAttribute('required', !this.checked);

        if (this.checked && contactNumber) {
            // Set password and confirm password fields to the contact number
            passwordInput.value = contactNumber;
            conPasswordInput.value = contactNumber;
        } else {
            // Clear the password fields if unchecked
            passwordInput.value = '';
            conPasswordInput.value = '';
        }

        passwordHelp.style.display = this.checked ? 'none' : 'block';
    });

    
</script>




</body>

</html>
