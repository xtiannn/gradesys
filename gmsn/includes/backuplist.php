<?php
session_start(); // Start the session to use session variables
require_once "config.php"; 

// Define the directory where backups are stored
$backupDir = './'; // Adjust this if your backups are stored in a different directory

// Get all files in the backup directory
$files = array_diff(scandir($backupDir), array('..', '.', '.DS_Store')); // Exclude . and .. directories

// Filter for .sql files only
$backupFiles = array_filter($files, function($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'sql';
});

// Get the backup file from the session
$lastBackupFile = isset($_SESSION['backup_file']) ? $_SESSION['backup_file'] : null;
unset($_SESSION['backup_file']); // Clear the session variable after using it

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup List</title>
    
    <!-- Link to your main CSS file -->
    <link href="../assets/css/style.css" rel="stylesheet"> <!-- Adjust the path if necessary -->

    <!-- Bootstrap CSS -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Button to open the modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#backupModal">
        View Backup Files
    </button>

    <!-- Modal -->
    <div class="modal fade" id="backupModal" tabindex="-1" aria-labelledby="backupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="backupModalLabel">Backup Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (count($backupFiles) > 0): ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Backup File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($backupFiles as $file): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($file); ?></td>
                                        <td><a href="<?php echo htmlspecialchars($file); ?>" download>Download</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No backup files available.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        // Show the modal if there's a last backup file
        <?php if ($lastBackupFile): ?>
            var modal = new bootstrap.Modal(document.getElementById('backupModal'));
            modal.show();
        <?php endif; ?>
    </script>
</body>
</html>
