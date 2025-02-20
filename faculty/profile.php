<?php 
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
  require_once("includes/config.php");



$userID = $_SESSION['userID'];

// Fetch user details
$query = "SELECT * FROM users WHERE userID = :userID";
$stmt = $conn->prepare($query);
$stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Profile - Grading System</title>
  <meta content="User profile page for Grading System" name="description">
  <meta content="profile, user, grading, education" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/gmsnlogo.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .form-control {
        border: none;
        border-radius: 0;
        border-bottom: 1px solid #ced4da;
        box-shadow: none;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #1a237e; /* Navy Blue Focus Color */
        box-shadow: 0 0 0 0.25rem rgba(26, 35, 126, 0.25); /* Navy Blue Shadow on Focus */
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

    .btn-primary {
        background-color: #1a237e; /* Navy Blue Button Color */
        border-color: #1a237e; /* Navy Blue Border Color */
    }

    .btn-primary:hover {
        background-color: #0d47a1; /* Darker Navy Blue on Hover */
        border-color: #0d47a1;
    }

    .btn-secondary {
        background-color: #6c757d; /* Gray Button Color */
        border-color: #6c757d; /* Gray Border Color */
    }

    .btn-secondary:hover {
        background-color: #5a6268; /* Darker Gray on Hover */
        border-color: #545b62;
    }
    #confirmPasswordField {
            display: none;
        }
        .password-feedback {
            display: none;
            font-size: 0.875em;
        }
        .password-mismatch {
            color: red;
        }
        .password-match {
            color: green;
        }
  </style>

</head>

<body>
  <?php require_once "support/header.php"; ?>
  <?php require_once "support/sidebar.php"; ?>

  <main id="main" class="main">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                <!-- Profile Form -->
                <form id="profileForm" class="needs-validation" novalidate>                
                    <fieldset class="border p-4 rounded mb-4">
                    <legend class="mb-4">Update Profile</legend>
                    <input type="hidden" name="userID"  value="<?php echo htmlspecialchars($user['userID']); ?>">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="txtuserlname" class="form-label fw-bold">Last Name:</label>
                            <input type="text" class="form-control me-5" id="txtuserlname" name="txtuserlname" placeholder="Last Name" value="<?php echo htmlspecialchars($user['lname']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="txtuserfname" class="form-label fw-bold">First Name:</label>
                            <input type="text" class="form-control me-5" id="txtuserfname" name="txtuserfname" placeholder="First Name" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="txtusermname" class="form-label fw-bold">Middle Name:</label>
                            <input type="text" class="form-control" id="txtusermname" name="txtusermname" placeholder="Middle Name" value="<?php echo htmlspecialchars($user['mname']); ?>">
                        </div>
                    </div>
                    <div class="invalid-feedback">Please provide a full name.</div>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="selGender" class="form-label fw-bold">Gender:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                </div>
                                <select class="form-select form-control selectpicker" id="selGender" name="selGender" required>
                                    <option disabled value>Select Gender</option>
                                    <option value="Male" <?php echo $user['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo $user['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                </select>
                                <div class="invalid-feedback">Please select a gender.</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="txtcontact" class="form-label fw-bold">Contact No.:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                </div>
                                <input type="number" class="form-control" name="txtcontact" id="txtcontact" placeholder="9-digits Contact No." value="<?php echo htmlspecialchars($user['contact']); ?>">
                                <div class="invalid-feedback">Please provide a 9-digit contact number.</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="txtemail" class="form-label fw-bold">Email:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" name="txtemail" id="txtemail" placeholder="Email" value="<?php echo htmlspecialchars($user['email']); ?>">
                                <div class="invalid-feedback">Please provide a valid email address.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="txtpass" class="form-label fw-bold">Password: <span class="fw-normal">(Leave blank if not changing)</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" name="txtpass" id="txtpass" placeholder="Password" 
                                    pattern="^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?&quot;:{}|<>]).{12,}$" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword" onclick="togglePasswordVisibility('txtpass', 'togglePassword')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">Password must be at least 12 characters long, contain an uppercase letter and a special character.</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3" id="confirmPasswordField">
                            <label for="txtconfirmpass" class="form-label fw-bold">Confirm Password:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" name="txtconfirmpass" id="txtconfirmpass" placeholder="Confirm Password" required>
                                <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword" onclick="togglePasswordVisibility('txtconfirmpass', 'toggleConfirmPassword')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">Please confirm your password.</div>
                            </div>
                            <div id="passwordFeedback" class="password-feedback">
                                <span id="passwordMatchMessage" class="password-match">Passwords match!</span>
                                <span id="passwordMismatchMessage" class="password-mismatch">Passwords do not match.</span>
                            </div>
                        </div>
                    </div>
                    </fieldset>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php require_once "support/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="assets/sweetalert2.all.min.js"></script> -->

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    // Password visibility toggle
    function togglePasswordVisibility(fieldId, buttonId) {
        var passwordField = document.getElementById(fieldId);
        var toggleButton = document.getElementById(buttonId);
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleButton.innerHTML = '<i class="bi bi-eye-slash"></i>'; // Change icon to eye-slash
        } else {
            passwordField.type = 'password';
            toggleButton.innerHTML = '<i class="bi bi-eye"></i>'; // Change icon to eye
        }
    }

    // Display confirm password field when password is entered
    document.getElementById('txtpass').addEventListener('input', function() {
        var confirmPasswordField = document.getElementById('confirmPasswordField');
        var passwordFeedback = document.getElementById('passwordFeedback');
        if (this.value) {
            confirmPasswordField.style.display = 'block';
        } else {
            confirmPasswordField.style.display = 'none';
            passwordFeedback.style.display = 'none';
        }
    });

    // Check password confirmation
    document.getElementById('txtconfirmpass').addEventListener('input', function() {
        var password = document.getElementById('txtpass').value;
        var confirmPassword = this.value;
        var passwordMatchMessage = document.getElementById('passwordMatchMessage');
        var passwordMismatchMessage = document.getElementById('passwordMismatchMessage');
        var passwordFeedback = document.getElementById('passwordFeedback');
        
        if (confirmPassword) {
            passwordFeedback.style.display = 'block';
            if (password === confirmPassword) {
                passwordMatchMessage.style.display = 'inline';
                passwordMismatchMessage.style.display = 'none';
            } else {
                passwordMatchMessage.style.display = 'none';
                passwordMismatchMessage.style.display = 'inline';
            }
        }
    });

    // Validate form on submit
    document.getElementById('profileForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var form = document.getElementById('profileForm');
        var formData = new FormData(form);

        fetch('process/update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Profile updated successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error updating profile: ' + data.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while updating the profile.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
</script>
</body>

</html>
