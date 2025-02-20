<?php
session_start();
require_once("includes/config.php");

if (!isset($_SESSION['userID'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['userID'];

$stmt = $conn->prepare("SELECT * FROM users WHERE userID = :userID");
$stmt->execute(['userID' => $userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Invalid email format!");</script>';
    } else {
        if (!empty($_POST['password']) && $_POST['password'] !== $_POST['cpassword']) {
            echo '<script>alert("Passwords do not match!");</script>';
        } else {
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'];

            if (!empty($_FILES['photo']['name'])) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_file_size = 2 * 1024 * 1024; // 2MB

                if (in_array($_FILES['photo']['type'], $allowed_types) && $_FILES['photo']['size'] <= $max_file_size) {
                    $target_dir = "uploads/photo/";
                    $target_file = $target_dir . basename($_FILES["photo"]["name"]);

                    $unique_file_name = $target_dir . uniqid() . '_' . basename($_FILES["photo"]["name"]);
                    move_uploaded_file($_FILES["photo"]["tmp_name"], $unique_file_name);
                } else {
                    echo '<script>alert("Invalid file type or size!");</script>';
                    $unique_file_name = $user['photo'];
                }
            } else {
                $unique_file_name = $user['photo'];
            }

            if (isset($_POST['delete_photo']) && $_POST['delete_photo'] === '1') {
                // Delete the existing photo file if it's not the default one
                if ($user['photo'] !== 'assets/img/default-profile.png' && 
                    $user['photo'] !== 'assets/img/maleUser.png' && 
                    $user['photo'] !== 'assets/img/femaleUser.png') {
                    @unlink($user['photo']);
                }
                // Set to default photo based on gender
                $unique_file_name = 'assets/img/default-profile.png';
                if (!empty($user['gender'])) {
                    $unique_file_name = ($user['gender'] === 'Male') ? 'assets/img/maleUser.png' : 'assets/img/femaleUser.png';
                }
            }

            // Update the user's information
            $stmt = $conn->prepare("UPDATE users SET fname = :fname, lname = :lname, gender = :gender, email = :email, password = :password, photo = :photo WHERE userID = :userID");
            $stmt->execute([
                'fname' => $fname,
                'lname' => $lname,
                'gender' => $_POST['gender'],
                'email' => $email,
                'password' => $password,
                'photo' => $unique_file_name,
                'userID' => $userID
            ]);

            // Set a session variable to trigger the alert on the profile page
            $_SESSION['profile_update_success'] = true;
            header('Location: profile.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>My Profile</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link href="assets/img/gmsnlogo.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

</head>
<body>
    <?php include("support/header.php"); ?>
    <?php include("support/sidebar.php"); ?>

    <main id="main" class="main mt-0">
        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="card shadow-sm p-3">
                            <div class="card-body">
                                <?php if (!empty($user['photo'])): ?>
                                    <div class="position-relative">
                                        <img src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                        <a href="#" id="delete-photo" class="position-absolute top-0 end-0 p-2" style="z-index: 10;">
                                        </a>
                                    </div>
                                <?php else:
                                    $defaultPhoto = 'assets/img/default-profile.png';
                                    if (!empty($user['gender'])) {
                                        $defaultPhoto = ($user['gender'] === 'Male') ? 'assets/img/maleUser.png' : 'assets/img/femaleUser.png';
                                    }
                                ?>
                                    <div class="position-relative">
                                        <img src="<?php echo $defaultPhoto; ?>" alt="Default Profile Picture" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                        <a href="#" id="delete-photo" class="position-absolute top-0 end-0 p-2" style="z-index: 10;">
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <h5 class="card-title">
                                    <?php
                                    $middleInitial = !empty($user['mname']) ? strtoupper($user['mname'][0]) . '.' : '';
                                    echo htmlspecialchars($user['fname'] . ' ' . $middleInitial . ' ' . $user['lname']);
                                    ?>
                                </h5>
                                <p class="text-muted"><?php echo htmlspecialchars($user['userType']); ?></p>
                            
                                <form action="profile.php" method="post" enctype="multipart/form-data" class="mt-3">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card shadow-sm p-3">
                            <div class="card-body">
                                <h2 class="mb-4">My Profile</h2>
                                <?php if (isset($_SESSION['profile_update_success'])): ?>
                                    <div class="alert alert-success" role="alert">
                                        Profile updated successfully!
                                    </div>
                                    <?php unset($_SESSION['profile_update_success']); ?>
                                <?php endif; ?>
                                <form action="profile.php" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="lname" class="form-label fw-bold">Last Name</label>
                                        <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fname" class="form-label fw-bold">First Name</label>
                                        <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="mname" class="form-label fw-bold">Middle Name</label>
                                                <input type="text" class="form-control" id="mname" name="mname" value="<?php echo htmlspecialchars($user['mname']); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label fw-bold">Gender</label>
                                                <select id="gender" name="gender" class="form-select" required>
                                                    <option value="Male" <?php echo ($user['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo ($user['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-bold">New Password <span class="fw-normal">(Leave blank if not changing.)</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" oninput="showConfirmPassword()">
                                            <span class="input-group-text" id="togglePassword" onclick="togglePasswordVisibility()">
                                                <i class="bi bi-eye" id="passwordIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3" id="confirmPasswordDiv" style="display: none">
                                        <label for="cpassword" class="form-label fw-bold">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="cpassword" name="cpassword">
                                            <span class="input-group-text" id="togglePassword" onclick="toggleConfirmPasswordVisibility()">
                                                <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="photo" class="form-label fw-bold">Profile Photo</label>
                                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include("support/footer.php"); ?>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        function showConfirmPassword() {
            var confirmPasswordDiv = document.getElementById('confirmPasswordDiv');
            var passwordInput = document.getElementById('password');

            confirmPasswordDiv.style.display = passwordInput.value ? 'block' : 'none';
        }

        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('password');
            var showPasswordIcon = document.getElementById('passwordIcon');

            // Toggle the type attribute based on the current state
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showPasswordIcon.classList.remove('bi-eye');
                showPasswordIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                showPasswordIcon.classList.remove('bi-eye-slash');
                showPasswordIcon.classList.add('bi-eye');
            }
        }

        function toggleConfirmPasswordVisibility() {
            var confirmPasswordInput = document.getElementById('cpassword');
            var confirmPasswordIcon = document.getElementById('confirmPasswordIcon');

            // Toggle the type attribute based on the current state
            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                confirmPasswordIcon.classList.remove('bi-eye');
                confirmPasswordIcon.classList.add('bi-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                confirmPasswordIcon.classList.remove('bi-eye-slash');
                confirmPasswordIcon.classList.add('bi-eye');
            }
        }

    </script>
</body>
</html>
