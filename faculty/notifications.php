<?php 
 session_start();

 IF (!isset($_SESSION['userID'])) {
   header('Location: ../logout.php');
   exit();
 } 
  require_once("includes/config.php");



$userID = $_SESSION['userID'];

// Fetch unread notifications for the logged-in user
$query = "SELECT message, created_at FROM notifications WHERE userID = :userID AND status = 'unread' ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch read notifications for the logged-in user
$historyQuery = "SELECT message, created_at FROM notifications WHERE userID = :userID AND status = 'read' ORDER BY created_at DESC";
$historyStmt = $conn->prepare($historyQuery);
$historyStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
$historyStmt->execute();
$notificationHistory = $historyStmt->fetchAll(PDO::FETCH_ASSOC);

// After fetching notifications, mark them as read
$updateQuery = "UPDATE notifications SET status = 'read' WHERE userID = :userID AND status = 'unread'";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
$updateStmt->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Notifications - Grading System</title>
  <meta content="Notifications page for Grading System" name="description">
  <meta content="grading, notifications, dashboard" name="keywords">

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
    /* Custom notification styling */
    .list-group-item {
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .notification-history {
        display: none;
        margin-top: 20px;
    }

    .notification-card {
        border: 1px solid #ddd;
        border-radius: 0.375rem;
        padding: 1.5rem;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        margin-bottom: 1rem;
        font-size: 1.25rem;
        font-weight: 500;
    }

    .notification-toggle-btn {
        margin-bottom: 1rem;
        display: flex;
        justify-content: center;
    }

    .notification-toggle-btn button {
        border-radius: 0.375rem;
        font-weight: 500;
    }

    .notification-item {
        display: flex;
        flex-direction: column;
    }

    .notification-item p {
        margin: 0;
    }

    .notification-item small {
        color: #6c757d;
    }

    .delete-btn {
        cursor: pointer;
        color: #dc3545;
        border: none;
        background: none;
    }

    .delete-btn:hover {
        color: #c82333;
    }

    .notification-history-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
    }
  </style>
</head>

<body>
  <?php require_once "support/header.php"; ?>
  <?php require_once "support/sidebar.php"; ?>

  <main id="main" class="container">
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card notification-card">
            <div class="card-body">
              <h5 class="card-title">Notifications</h5>
              <div id="notification-list" class="list-group">
                <?php if (empty($notifications)): ?>
                  <p class="text-muted">No new notifications.</p>
                <?php else: ?>
                  <?php foreach ($notifications as $notification): ?>
                    <div class="list-group-item notification-item">
                      <p><?php echo htmlspecialchars($notification['message']); ?></p>
                      <small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($notification['created_at'])); ?></small>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Toggle Button -->
          <div class="notification-toggle-btn">
            <button id="toggle-history-btn" class="btn btn-primary">Show Notification History</button>
          </div>

          <!-- Notification History -->
          <div id="notification-history" class="notification-history">
            <div class="card notification-card">
              <div class="card-body">
                <h5 class="card-title">Notification History</h5>
                <div id="history-list" class="list-group">
                  <?php if (empty($notificationHistory)): ?>
                    <p class="text-muted">No previous notifications.</p>
                  <?php else: ?>
                    <?php foreach ($notificationHistory as $notification): ?>
                      <div class="list-group-item notification-item">
                        <p><?php echo htmlspecialchars($notification['message']); ?></p>
                        <small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($notification['created_at'])); ?></small>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
                <!-- Delete Icon Button -->
                <div class="notification-history-actions">
                  <button id="delete-history-btn" class="delete-btn">
                    <i class="bi bi-trash"></i> Delete All
                  </button>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
  </main>

  <?php require_once "support/footer.php"; ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Custom JS -->
  <script src="assets/js/main.js"></script>

  <script>
    document.getElementById('toggle-history-btn').addEventListener('click', function() {
      var historySection = document.getElementById('notification-history');
      var button = document.getElementById('toggle-history-btn');
      
      if (historySection.style.display === 'none' || historySection.style.display === '') {
        historySection.style.display = 'block';
        button.textContent = 'Hide Notification History';
      } else {
        historySection.style.display = 'none';
        button.textContent = 'Show Notification History';
      }
    });

    document.getElementById('delete-history-btn').addEventListener('click', function() {
      Swal.fire({
        title: 'Confirmation Required.',
        text: "Do you want to delete all notification history?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete'
      }).then((result) => {
        if (result.isConfirmed) {
          // Perform the delete operation
          fetch('delete_notifications.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'action=delete_all'
          }).then(response => response.json()).then(data => {
            if (data.success) {
              Swal.fire(
                'Deleted!',
                'All notifications have been deleted.',
                'success'
              ).then(() => {
                // Refresh the page or update the UI
                location.reload();
              });
            } else {
              Swal.fire(
                'Error!',
                'There was a problem deleting notifications.',
                'error'
              );
            }
          });
        }
      });
    });
  </script>

</body>

</html>




