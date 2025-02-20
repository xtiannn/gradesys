<?php
session_start(); // Start the session

if(isset($_SESSION['userID'])) {
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
  background-color: #1a237e; /* Navy Blue Background */
  color: #ffffff; /* White Text */
  padding: 20px;
  text-align: center;
}
.white-text {
  color: white;
}
    
</style>

<?php
require_once("includes/config.php");
$query = "SELECT ay.*,s.semName 
FROM academic_year ay
JOIN semester s ON ay.semID = s.semID";
$stmt = $conn->prepare($query);
$stmt->execute();
$acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC);  
 foreach ($acadYear as $row): endforeach
?>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">


  <div class="d-flex align-items-center justify-content-between">
    <a href="index.php" class="logo d-flex align-items-center" style="text-decoration: none">
        <img src="assets/img/gmsnlogo.png" alt="" style="height: 45px; width: 45px; max-height: 100px; max-width: 80px; margin: 0;">
        <span class="d-none d-lg-block" style="font-size: 20px; color: white; margin-left: 20px;">Grace Montessori</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn" style="color: white;"></i>
</div>


<!-- End Logo -->


<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-auto" style="width:;"> 
        <?php if (!empty($acadYear)): ?>
                <?php foreach ($acadYear as $row): ?>
                    <a class="logo d-flex align-items-center" style="text-decoration: none;">
                        <span class="d-none d-lg-block" style="font-size: 17px; color: white; max-width: 300px; word-wrap: break-word; white-space: normal;">
                            A.Y. <?php echo($row['ayName'].' - '.$row['semName']); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No active sessions found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
      <?php if(isset($user)) : ?>
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <!-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->
          <span class="d-none d-md-block dropdown-toggle ps-2 white-text">
          <?php
          // Check if the faculty is male or female
          if ($user['gender'] == 'Male') {
            echo 'Mr. ' . $user['lname']; 
          } else {
            echo 'Ms. ' . $user['lname'];
          }
          ?>

        </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6><?php echo $user['lname'].', '.$user['fname'].' '.$user['mname']; ?></h6>
            <span><?php echo $user['userType']; ?></span>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-person"></i><span>My Profile</span></a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item d-flex align-items-center" href="#"><i class="bi bi-gear"></i><span>Account Settings</span></a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
              <a class="dropdown-item d-flex align-items-center" href="/cap/logout.php">
                  <i class="bi bi-box-arrow-right"></i>
                  <span>Sign Out</span>
              </a>
          </li>
        </ul>
      </li>
      <?php endif; ?>
    </ul>
  </nav>

  <!-- End Icons Navigation -->

</header><!-- End Header -->