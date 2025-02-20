
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css"> -->

<?php

if(isset($_SESSION['userID'])) {
  require_once("includes/config.php");

    $query = "SELECT s.*, (SELECT userType FROM user_type WHERE TypeID = s.userTypeID) AS userType 
    FROM users s WHERE s.userID = :userID";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>



<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-heading">
      <?php if(isset($user)) : ?>
        <i class="bi bi-person"></i> <?php echo $user['userType']. ': ' .$user['lname'].', '.$user['fname'].' '.$user['mname']?>
      <?php endif; ?>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="index.php">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="assignedSub.php">
        <i class="bi bi-pencil-square"></i>
        <span>Assigned Subjects</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="view_grades.php">
        <i class="bi bi-eye"></i>
        <span>View Grades</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="manage_students.php">
        <i class="bi bi-people"></i>
        <span>Advisory Class</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="notifications.php">
        <i class="bi bi-bell"></i>
        <span>Notifications</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="profile.php">
        <i class="bi bi-person-circle"></i>
        <span>Profile</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="logout.php">
        <i class="bi bi-box-arrow-right"></i>
        <span>Logout</span>
      </a>
    </li>
  </ul>
</aside><!-- End Sidebar-->

  


<?php
require_once("modals/AYModal.php");
require_once 'includes/config.php';

// Fetch current values and semID
try {
    // Fetch semID
    $sql = "SELECT semID FROM academic_year";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $semID = $result['semID'];

    // Fetch current switch values
    $sql = "SELECT _first, _second FROM gradepermission"; 
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $firstSwitchValue = $result['_first'];
    $secondSwitchValue = $result['_second'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>




