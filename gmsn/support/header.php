<?php

// if (session_status() == PHP_SESSION_NONE) {
//   session_start();
// }

if (isset($_SESSION['userID'])) {
  require_once("includes/config.php");
  $query = "SELECT a.*, ut.userType
            FROM users a
            JOIN user_type ut ON a.userTypeID = ut.typeID
            WHERE userID = :userID";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
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
</style>


<?php
require_once("includes/config.php");
$query = "SELECT ay.*, s.semName 
          FROM academic_year ay
          JOIN semester s ON ay.semID = s.semID";
$stmt = $conn->prepare($query);
$stmt->execute();
$acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between logo-wrapper">
    <a href="dashboard.php" class="logo" style="text-decoration: none;">
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
        <?php foreach ($acadYear as $row): ?>
          <span>A.Y. <?php echo $row['ayName'] . ' ' . strtoupper($row['semName']); ?></span>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color: white;">No active sessions found.</p>
      <?php endif; ?>
    </div>
  </div>

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
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
            <li><a class="dropdown-item d-flex align-items-center" href="userUpdate.php?userID=<?php echo $user['uid']?>"><i class="bi bi-person"></i><span>My Profile</span></a></li>
            <li><hr class="dropdown-divider"></li>
            <!-- <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-gear"></i><span>Account Settings</span></a></li> -->
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


