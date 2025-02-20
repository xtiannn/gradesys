<?php
require_once("includes/config.php");

if (isset($_SESSION['userID']) && !empty($_SESSION['userID'])) {
  // Continue with query
} else {
  echo "Error: User is not logged in.";
  exit;
}


// Fetch user info
if (isset($_SESSION['userID'])) {
  $query = "SELECT a.*, ut.userType
            FROM users a
            JOIN user_type ut ON a.userTypeID = ut.typeID
            WHERE userID = :userID";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
  
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if user data was found
  if ($user) {
      // Set session variables
      $_SESSION['isDefault'] = $user['isDefault'];
      $_SESSION['userTypeID'] = $user['userTypeID'];

      $isDefault = $_SESSION['isDefault'];
      $uTypeID = $_SESSION['userTypeID'];
      $uid = $user['uid'];

      // Handle password change alert
      if ($isDefault == 1 && !isset($_SESSION['password_change_alert_shown'])) {
          $_SESSION['password_change_alert_shown'] = true;
          echo "<script src='../gmsn/assets/sweetalert2.all.min.js'></script>";
          echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Change Password Required',
                    text: 'You are using the default password. Please change it to ensure your account is secure.',
                    showConfirmButton: true,
                    confirmButtonText: 'Change',
                    showCancelButton: true,
                    cancelButtonText: 'Later',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../gmsn/userUpdate.php?userID=" . $user['uid'] . "';
                    }
                });
            </script>";
      }
  } else {
      // Handle the case where no user was found
      echo "Error: User not found.";
      exit;
  }
}


// Fetch notifications
$query = "SELECT COUNT(*) AS unread_count FROM notifications WHERE userID = :userID AND status = 'unread'";
$stmt = $conn->prepare($query);
$stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
$stmt->execute();
$notificationCount = $stmt->fetchColumn();

$notificationQuery = "SELECT message, created_at FROM notifications WHERE userID = :userID AND status = 'unread' ORDER BY created_at DESC LIMIT 5";
$notificationStmt = $conn->prepare($notificationQuery);
$notificationStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
$notificationStmt->execute();
$notifications = $notificationStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch academic year
$query = "SELECT ay.*, s.semName 
          FROM academic_year ay
          JOIN semester s ON ay.semID = s.semID";
$stmt = $conn->prepare($query);
$stmt->execute();
$acadYear = $stmt->fetch(PDO::FETCH_ASSOC);
$ay = $acadYear['ayName'];
$sem = $acadYear['semID'];
?>

<style>
.header {
    background-color: #1a237e; 
    color: #ffffff; 
    padding: 15px 20px; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header .logo {
    display: flex;
    align-items: center;
}

.header .logo span {
    font-size: 20px;
    color: #ffffff;
    margin-top: 2px;
    font-weight: bold; 
    text-transform: uppercase; 
}

.header .header-nav .nav-item .nav-link {
    color: #ffffff;
    padding: 10px;
    transition: background-color 0.3s ease;
}

.header .header-nav .nav-item .nav-link:hover {
    background-color: #3949ab; 
    border-radius: 5px; 
}

.header .header-nav .nav-item .nav-profile img {
    width: 40px;
    height: 40px;
    margin-right: 10px;
}

.header .header-nav .nav-item .nav-profile .white-text {
    font-weight: bold;
}

.header .header-nav .dropdown-menu {
    background-color: #f9f9f9; 
    border: 1px solid #e0e0e0; 
    border-radius: 5px; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
}

.header .header-nav .dropdown-menu .dropdown-item {
    color: #333; 
    padding: 10px 20px;
    font-size: 14px; 
    transition: background-color 0.3s ease, color 0.3s ease;
}

.header .header-nav .dropdown-menu .dropdown-item:hover {
    background-color: #e0e0e0; 
    color: #1a237e; 
}

.header .header-nav .dropdown-menu .dropdown-item i {
    margin-right: 10px;
}

.logo-text {
  color: white;
  text-align: center; 
  line-height: 1.2;
}

.school-name {
  font-size: 1.20rem; 
  font-weight: bold;
  margin-bottom: 0; 
}

.school-subtitle {
  font-size: 0.850rem; 
  margin-top: 0; 
}

.header .container-fluid {
    display: flex;
    justify-content: center;
    align-items: center;
}

.header .container-fluid .col-auto {
    text-align: center;
}

.header .container-fluid .col-auto span {
    font-size: 17px;
    font-family: 'Arial', sans-serif; 
    color: #ffffff;
    font-weight: bold;
    white-space: nowrap;
}
.header-nav .notification-dropdown {
    background-color: #ffffff; 
    border: 1px solid #e0e0e0; 
    border-radius: 5px; 
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
    max-height: 300px; 
    overflow-y: auto; 
    position: absolute; 
    top: 100%; 
    left: 50%; 
    transform: translateX(-50%); 
    z-index: 1000;
}

.header-nav .notification-dropdown .dropdown-item {
    color: #333; 
    padding: 10px 20px;
    font-size: 14px; 
    transition: background-color 0.3s ease, color 0.3s ease;
}

.header-nav .notification-dropdown .dropdown-item:hover {
    background-color: #f1f1f1; 
    color: #1a237e; 
}

.header-nav .notification-dropdown .dropdown-item small {
    display: block;
    color: #6c757d;
    font-size: 12px;
}

.header-nav .nav-item {
    position: relative;
}

</style>


<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between logo-wrapper">
    <a href="<?php echo ($_SESSION['userTypeID'] == 1) ? 'dashboard.php' : '../faculty/index.php'?>" class="logo" style="text-decoration: none;">
      <img src="assets/img/gmsnlogo.png" alt="School Logo" style="height: 45px; width: auto; margin-left: -10px; max-height: 90px;">
      <div class="logo-text">
        <div class="school-name">GRACE MONTESSORI</div>
        <div class="school-subtitle">SCHOOL OF NOVALICHES</div>
      </div>
    </a>
    <i class="bi bi-list toggle-sidebar-btn" style="color: white; font-size: 1.5rem; cursor: pointer;"></i>
  </div>

  <div class="container-fluid">
    <div class="col-auto">
      <?php if (!empty($acadYear)): ?>
          <span>A.Y. <?php echo $acadYear['ayName'] . ' ' . strtoupper($acadYear['semName']); ?></span>
      <?php else: ?>
        <p style="color: white;">No active sessions found.</p>
      <?php endif; ?>
    </div>
  </div>

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

<!-- Notification Dropdown -->
<li class="nav-item dropdown pe-3">
  <a class="nav-link nav-notification d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
    <i class="bi bi-bell-fill me-2"></i>
    <?php if ($notificationCount > 0): ?>
      <span class="badge notification-active"><?php echo $notificationCount; ?></span>
    <?php endif; ?>
  </a>
  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notification-dropdown">
    <li class="dropdown-header">
      <h6>Notifications</h6>
    </li>
    <li><hr class="dropdown-divider"></li>
    <?php if (empty($notifications)): ?>
      <li><a class="dropdown-item" href="#">No new notifications.</a></li>
    <?php else: ?>
      <?php foreach ($notifications as $notification): ?>
        <li>
          <a class="dropdown-item" href="#">
            <div><?php echo htmlspecialchars($notification['message']); ?></div>
            <small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($notification['created_at'])); ?></small>
          </a>
        </li>
      <?php endforeach; ?>
    <?php endif; ?>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-center" href="notifications.php">View all notifications</a></li>
  </ul>
</li>



      <?php if (isset($user)) : ?>
        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <?php
              $profile = !empty($user['photo']) ? $user['photo'] : ($user['gender'] == 'Male' ? 'assets/img/maleUser.png' : 'assets/img/femaleUser.png');
            ?>
            <img src="<?php echo $profile; ?>" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2 white-text">
              <?php echo $user['gender'] == 'Male' ? 'Mr. ' . $user['lname'] : 'Ms. ' . $user['lname']; ?>
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $user['lname'] . ', ' . $user['fname'] . ' ' . $user['mname']; ?></h6>
              <span><?php echo $user['userType']; ?></span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item d-flex align-items-center" href="../gmsn/userUpdate.php?userID=<?php echo $uid?>&uTypeID=<?php echo $uTypeID?>"><i class="bi bi-person"></i><span>My Profile</span></a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
            <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" onclick="confirmLogout()">
                <i class="bi bi-box-arrow-right"></i><span>Sign Out</span>
            </a>
            </li>
          </ul>
        </li>
      <?php endif; ?>
    </ul>
  </nav>

</header><!-- End Header -->
<script src="assets/sweetalert2.all.min.js"></script>

<script>
  function confirmLogout() {
      Swal.fire({
          title: 'Confirm Sign-Out',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sign Out',
          cancelButtonText: 'Cancel',
          reverseButtons: true
      }).then((result) => {
          if (result.isConfirmed) {
              window.location.href = '/cap/logout.php';
          }
      });
  }
</script>


