<?php
    include 'session.php';
    require_once("includes/config.php");

    $user_id = $_GET['userID'] ?? NULL;
    $uTypeID = $_GET['uTypeID'] ?? NULL;

    $sqlUser = "SELECT * FROM users WHERE uid = :uid";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bindParam(':uid', $user_id, PDO::PARAM_INT);
    $stmtUser->execute();
    $row = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $uid = $row['uid'];
    $userTypeID = $row['userTypeID'];
    $userID = $row['userID'];
    $lname = $row['lname'];
    $fname = $row['fname'];
    $mname = $row['mname'];
    $sex = $row['gender'];
    $contact = $row['contact'];
    $email = $row['email'];
    $photoPath = $row['photo'];
    $existingPassword = $row['password'];
    $existingContact = $row['contact'] ?? NULL;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Update User Account</title>
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
                        <li class="breadcrumb-item active" aria-current="page">User Update</li>
                    </ol>
                </nav>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row"></div>
                                <form id="facultyForm" action="save_faculty.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                                    <input type="hidden" name="uid" value="<?php echo $uid?>">
                                    <fieldset class="border p-4 rounded mb-4">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="row me-2">
                                                    <div class="col-md-11 d-flex align-items-center">
                                                        <legend>Update User Account</legend>
                                                    </div>
                                                </div>
                                                <div class="row mb-2 mt-2">
                                                    <div class="col-md-5 mb-3">
                                                        <input type="hidden" name="uTypeID" id="" value="<?php echo $uTypeID?>">
                                                        <input type="hidden" name="existingPassword" id="" value="<?php echo $existingPassword?>">
                                                        <input type="hidden" name="existingContact" id="" value="<?php echo $existingContact?>">
                                                        <label for="selType" class="form-label fw-bold">User Type: <span style="color: red; font-size: 18px">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                                            </div>

                                                            <?php if($uTypeID == 2):?>
                                                                <input type="text" class="form-control me-3" id="" name="" placeholder="" value="Faculty" readonly>
                                                                <input type="hidden" class="form-control me-3" id="" name="selType" placeholder="" value="<?php echo $uTypeID?>" readonly>
                                                            <?php else:?>
                                                                <select class="form-select form-control" id="selType" name="selType" required>
                                                                    <option selected disabled value="">Select User Type</option>
                                                                    <option value="1"<?php echo ($userTypeID == 1) ? 'selected' : ''?>>Admin</option>
                                                                    <option value="2"<?php echo ($userTypeID == 2) ? 'selected' : ''?>>Faculty</option>
                                                                </select>                                                            <?php endif;?>
                                                            <div class="invalid-feedback">Please select a user type.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="userID" class="form-label fw-bold">User ID: <span style="color: red; font-size: 18px">*</span></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control me-3" id="userID" name="txtuserID" placeholder="User ID" 
                                                                   value="<?php echo $userID?>" <?php echo ($uTypeID == 2) ? 'readonly' : ''?> required>
                                                            <div class="invalid-feedback">Please provide a user ID.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 me-0">
                                                        <label for="txtuserlname" class="form-label fw-bold">Last Name: <span style="color: red; font-size: 18px">*</span></label>
                                                            <input type="text" value="<?php echo ucwords(strtolower(trim($lname)))?>"
                                                                class="form-control" id="txtuserlname" name="txtuserlname" placeholder="eg. Dela Cruz" required>
                                                            <div class="invalid-feedback">Please provide a last name.</div>
                                                    </div>
                                                    <div class="col-md-4 ms-0">
                                                        <label for="txtuserfname" class="form-label fw-bold">First Name: <span style="color: red; font-size: 18px">*</span></label>
                                                        <div class="input-group">
                                                            <input type="text" value="<?php echo ucwords(strtolower(trim($fname)))?>" class="form-control" id="txtuserfname" name="txtuserfname" placeholder="eg. Juan" required>
                                                            <div class="invalid-feedback">Please provide a last name.</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 ms-0 mt-1">
                                                        <label for="txtusermname" class="form-label fw-bold">Middle Name: </label>
                                                        <div class="input-group">
                                                            <input type="text" value="<?php echo ucwords(strtolower(trim($mname)))?>" class="form-control" id="txtusermname" name="txtusermname" placeholder="(Optional)">
                                                            <div class="invalid-feedback">Please provide a last name.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Image Preview -->
                                            <div class="col-md-2 d-flex flex-column align-items-end mt-2">
                                                <div class="position-relative mb-2">
                                                    <div id="imagePreviewContainer">
                                                        <img id="imagePreview" 
                                                            src="<?php echo ($photoPath == NULL) ? 'assets/img/user.png' : $photoPath?>" 
                                                            alt="User Photo">
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
                                                        <option value="Male"<?php echo ($sex === 'Male') ? 'selected' : ''?>>Male</option>
                                                        <option value="Female"<?php echo ($sex === 'Female') ? 'selected' : ''?>>Female</option>
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
                                                    value="<?php echo $contact?>" 
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
                                                    <input type="email" value="<?php echo strtolower(trim($email))?>" class="form-control" id="txtemail" name="txtemail" placeholder="email@example.com" required>
                                                    <div class="invalid-feedback">Please provide an email address.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="userPassword" class="form-label fw-bold">Password: <span style="color: red; font-size: 18px">*</span>
                                                    <span class="fw-normal">(Leave blank if not changing.)</span>
                                                </label> 
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                    </div>
                                                    <input type="password" 
                                                            class="form-control" id="userPassword" 
                                                            name="userPassword" 
                                                            placeholder="Password" 
                                                            oninput="validatePasswordRequirement(this)"
                                                            >
                                                    <button class="btn btn-outline-secondary" type="button" id="passwordToggle">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                                <small id="passwordHelp" class="form-text text-muted d-none">
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
                                                            >
                                                    <button class="btn btn-outline-secondary" type="button" id="conPasswordToggle">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small id="userPasswordHelp" class="form-text"></small>
                                        </div>
                                    </fieldset>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <button type="submit" class="btn btn-primary me-md-2" name="<?php echo ($uTypeID == 2) ? 'updateProfileBtn' : 'updateFacultyBtn'?>">
                                        <i class="bi bi-save"></i> Update 
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
    <script src="assets/sweetalert2.all.min.js"></script>


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
        const hasUpperCase = /[A-Z]/.test(password); // At least one uppercase letter
        const hasSymbol = /[!@#$%^&*(),.?":{}|<>]/.test(password); // At least one symbol

        // Set custom validity based on conditions
        if (!minLength) {
            input.setCustomValidity("Password must be at least 12 characters long.");
        } else if (!hasUpperCase) {
            input.setCustomValidity("Password must contain at least one uppercase letter.");
        } else if (!hasSymbol) {
            input.setCustomValidity("Password must contain at least one symbol.");
        } else {
            input.setCustomValidity(""); // Clear any error if all conditions are met
        }

        input.reportValidity(); // Trigger validity check

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

        // Ensure the passwordHelp is visible by removing the d-none class
        if (passwordHelp.classList.contains('d-none')) {
            passwordHelp.classList.remove('d-none');
        }

        // Update the input field's style
        if (!minLength || !hasUpperCase || !hasSymbol) {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid'); // Add 'is-invalid' class for warning color (red)
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid'); // Add 'is-valid' class for success color (green)
        }
    }

    // Add event listener for oninput to trigger validation on password field
    document.getElementById('userPassword').addEventListener('input', function() {
        validatePasswordRequirement(this);
    });


    (function () {
    'use strict';

    const forms = document.querySelectorAll('.needs-validation');
    const passwordInput = document.getElementById('userPassword');
    const conPasswordInput = document.getElementById('userConPassword');
    const photoInput = document.getElementById('photo');
    const mnameInput = document.getElementById('mname');
    const passwordToggle = document.getElementById('passwordToggle');
    const conPasswordToggle = document.getElementById('conPasswordToggle');

    // Function to validate password length
    function validatePassword(input) {
        const minLength = input.value.length >= 12;
        const isUpdating = input.hasAttribute('data-updating') && input.getAttribute('data-updating') === 'true';  // Check if we're updating

        // If not updating and password is empty, skip validation
        if (!isUpdating && input.value === '') {
            input.setCustomValidity('');  // No validation for empty password if not updating
            return;
        }

        // Validate if password is empty but updating
        if (isUpdating && input.value === '') {
            input.setCustomValidity('');  // Allow empty if updating
            return;
        }

        // Validate length if password is not empty
        if (!minLength) {
            input.setCustomValidity('Password must be at least 12 characters long.');
        } else {
            input.setCustomValidity(''); // Clear custom validation message
        }
    }

    // Function to validate password confirmation
    function validateConPassword(password, conPassword) {
        const isUpdating = password.hasAttribute('data-updating') && password.getAttribute('data-updating') === 'true';  // Check if we're updating

        if (isUpdating && conPassword.value === '') {
            // Allow empty confirmation if updating password
            conPassword.setCustomValidity('');
            return;
        }

        if (conPassword.value !== password.value) {
            conPassword.setCustomValidity('Passwords do not match.');
        } else {
            conPassword.setCustomValidity(''); // Clear custom validation message
        }
    }

    // Add validation to forms
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            validatePassword(passwordInput); // Validate password length
            validateConPassword(passwordInput, conPasswordInput); // Validate password confirmation


            // Prevent form submission if passwords do not match
            if (passwordInput.value !== conPasswordInput.value) {
                event.preventDefault();
                event.stopPropagation();

                // Show SweetAlert instead of the plain alert
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords do not match!',
                    text: 'Please make sure both passwords match before submitting.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Skip validation for userPassword, userConPassword, photo, and mname
            if (passwordInput.value === '' && !passwordInput.hasAttribute('data-updating')) {
                passwordInput.removeAttribute('required');
            } else {
                passwordInput.setAttribute('required', true);
            }

            if (conPasswordInput.value === '' && !conPasswordInput.hasAttribute('data-updating')) {
                conPasswordInput.removeAttribute('required');
            } else {
                conPasswordInput.setAttribute('required', true);
            }

            if (photoInput.files.length === 0) {
                photoInput.removeAttribute('required');
            } else {
                photoInput.setAttribute('required', true);
            }

            if (mnameInput.value === '') {
                mnameInput.removeAttribute('required');
            } else {
                mnameInput.setAttribute('required', true);
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });

    // Add real-time validation for password and confirmation fields
    passwordInput.addEventListener('input', () => {
        validatePassword(passwordInput);
        validateConPassword(passwordInput, conPasswordInput); // Re-validate confirmation on password change
    });

    conPasswordInput.addEventListener('input', () => {
        validateConPassword(passwordInput, conPasswordInput); // Re-validate on confirmation input
    });

    // Toggle password visibility
    const togglePasswordVisibility = (input, toggleButton) => {
        toggleButton.addEventListener('click', () => {
            const type = input.type === 'password' ? 'text' : 'password';
            input.type = type;

            // Change icon based on the current type
            toggleButton.innerHTML = type === 'password'
                ? '<i class="bi bi-eye"></i>'
                : '<i class="bi bi-eye-slash"></i>'; // Toggle "eye" and "eye-slash" icons
        });
    };

    // Apply toggle functionality for all password fields
    if (passwordToggle) togglePasswordVisibility(passwordInput, passwordToggle);
    if (conPasswordToggle) togglePasswordVisibility(conPasswordInput, conPasswordToggle);

})();

    



</script>




</body>

</html>
