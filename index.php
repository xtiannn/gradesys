<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['userID']) && isset($_SESSION['userTypeID'])) {
    // Redirect based on userTypeID
    if ($_SESSION['userTypeID'] == 1) {
        header("Location: gmsn/dashboard.php"); // Admin path
    } else if ($_SESSION['userTypeID'] == 2) {
        header("Location: faculty/index.php"); // Faculty path
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "includes/config.php";

    // Function to handle user authentication
    function authenticateUser($conn, $username, $password) {
        try {
            // Retrieve userTypeID and account status from users table
            $sql_utype = "SELECT userTypeID, password, isActive FROM users WHERE userID = :userID";
            $stmt_utype = $conn->prepare($sql_utype);
            $stmt_utype->bindParam(':userID', $username, PDO::PARAM_STR);
            $stmt_utype->execute();

            if ($stmt_utype->rowCount() == 1) {
                $user = $stmt_utype->fetch(PDO::FETCH_ASSOC);
                $userTypeID = $user['userTypeID'];
                $hashed_password_from_db = $user['password'];
                $isActive = $user['isActive'];

                // Verify the password
                if (password_verify($password, $hashed_password_from_db)) {
                    // Check if the account is active
                    if ($isActive == 1) {
                        // Set session variables only if the account is active
                        $_SESSION['userID'] = $username;
                        $_SESSION['userTypeID'] = $userTypeID;

                        // Redirect based on userTypeID
                        if ($userTypeID == 1) {
                            header("Location: gmsn/dashboard.php?login=success"); // Admin path
                        } else if ($userTypeID == 2) {
                            header("Location: faculty/index.php?login=success"); // Faculty path
                        }
                        exit();
                    } else {
                        // Account is inactive, redirect with error message
                        header("Location: index.php?error=account_inactive");
                        exit();
                    }
                } else {
                    header("Location: index.php?error=password");
                    exit();
                }
            } else {
                header("Location: index.php?error=user_not_found");
                exit();
            }
        } catch (PDOException $e) {
            // Log error instead of echoing
            error_log("Error: " . $e->getMessage());
            header("Location: index.php?error=internal_error");
            exit();
        }
    }


    // Sanitize input
    $username = filter_input(INPUT_POST, 'userID', FILTER_SANITIZE_STRING);
    $input_password = $_POST['password'];

    // Authenticate user
    authenticateUser($conn, $username, $input_password);

    // Close the database connection
    unset($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login Form - Grace Montessori School of Novaliches</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="gmsn/assets/img/gmsnlogo.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


  <script type="text/javascript">
        function preventBack() {window.history.forward()};
        setTimeout("preventBack()", 0);
            window.onunload=function(){null;}
  </script>
<style>
    body {
    background-image: url("gmsn/assets/img/gmsnCover.png");
    background-size: cover;
    background-position: center;
    font-family: 'Open Sans', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    transition: background-color 0.3s ease;
}

.login-container {
    background-color: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 10px;
    max-width: 400px;
    width: 100%;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    opacity: 0;
    animation: fadeIn 1s forwards;

    position: relative; 
    left: -30px; 
}

.login-container img {
    max-width: 150px;
    margin-bottom: 20px;
    transition: transform 0.3s ease-in-out;
}

.login-container img:hover {
    transform: scale(1.2); 
}


.login-container h2 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 28px;
    font-weight: 1000;
    color: #333;
    transition: color 0.3s ease;
}

.login-form {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}


.login-form input[type="text"],
.login-form input[type="password"] {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    box-sizing: border-box; 
}

.login-form input[type="text"]:focus,
.login-form input[type="password"]:focus {
    border-color: #007bff;
    transform: scale(1.05);
}

.password-container {
    position: relative;
    width: 100%; /* Ensure it spans the full width of the container */
    margin-bottom: 20px;
}

.password-container input[type="password"] {
    width: 100%;
    padding-right: 40px; /* Add space for the icon */
    box-sizing: border-box;
}


.login-form input[type="text"]:focus::placeholder,
.login-form input[type="password"]:focus::placeholder {
    opacity: 0.7;
}

.login-form button {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.login-form button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.login-form button:focus {
    outline: none;
}

.error-message {
    color: red;
    font-size: 14px;
    margin-top: 10px;
    transition: opacity 0.3s ease;
}

@media (max-width: 576px) {
    .login-container {
        padding: 20px;
        max-width: 90%;
    }

    .login-container h2 {
        font-size: 20px;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

#togglePassword {
    position: absolute;
    top: 37%;
    right: 20px;
    transform: translateY(-50%);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

</style>
</head>
<body>
 
<main id="main" class="main">
    <div class="login-container">
        <img src="gmsn/assets/img/gmsnlogo.png" alt="Grace Montessori Logo">
        <h2>GRADING SYSTEM</h2>
        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" novalidate>
            <input type="text" name="userID" placeholder="User ID" required>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span id="togglePassword">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                </span>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</main>
  
  <script src="gmsn/assets/sweetalert2.all.min.js"></script>


  <!-- Template Main JS File -->
  <script src="gmsn/assets/js/main.js"></script>
  <script>
    <?php
    if (isset($_GET['error'])) {
        // This will display the SweetAlert based on the error code
        if ($_GET['error'] == 'password') {
            echo "Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: 'The User ID or Password you entered is incorrect. Please try again.',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }).then(() => {
                window.history.replaceState(null, null, window.location.pathname);
            });";
        } elseif ($_GET['error'] == 'user_not_found') {
            echo "Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: 'User ID not found. Please try again.',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }).then(() => {
                window.history.replaceState(null, null, window.location.pathname);
            });";
        } elseif ($_GET['error'] == 'account_inactive') {
            echo "Swal.fire({
                icon: 'error',
                title: 'Account Inactive',
                text: 'Your account is currently inactive. It may not be activated yet or has been deactivated. Please contact the administrator for further assistance.',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }).then(() => {
                window.history.replaceState(null, null, window.location.pathname);
            });";
        } elseif ($_GET['error'] == 'internal_error') {
            echo "Swal.fire({
                icon: 'error',
                title: 'Internal Error',
                text: 'An error occurred. Please try again later.',
                showConfirmButton: true,
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }).then(() => {
                window.history.replaceState(null, null, window.location.pathname);
            });";
        }
    }
    ?>
</script>

  <script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

</script>

<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        const passwordField = document.getElementById("password");
        const icon = this.querySelector("i");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
</script>




</body>

</html>