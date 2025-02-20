<?php

// Include configuration file
require_once("includes/config.php");

// Backup directory
$backupDir = '/cap/gmsn/backup/';

// Ensure backup directory exists or create it
if (!file_exists($backupDir)) {
    if (!mkdir($backupDir, 0755, true)) {
        $response = array(
            'status' => 'error',
            'message' => 'Unable to create backup directory.'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit; // Exit script if unable to create directory
    }
}

// Create backup filename with current timestamp
$backupFile = $backupDir . 'backup_' . date('Y-m-d_H-i-s') . '.sql';

// Command to create database backup using mysqldump (specify full path)
$command = "/usr/bin/mysqldump -h $host -u $username -p$password $dbname > $backupFile";

// Execute the backup command
exec($command, $output, $returnValue);

if ($returnValue === 0) {
    // Backup successful
    $response = array(
        'status' => 'success',
        'message' => 'Database backup completed successfully.',
        'backup_file' => $backupFile  // Provide the backup file path for reference
    );
} else {
    // Backup failed
    $response = array(
        'status' => 'error',
        'message' => 'Database backup failed. Please check the error logs.',
        'error' => implode("\n", $output)  // Provide any error output for debugging
    );
    // Log errors to a file for troubleshooting
    error_log('Database backup failed: ' . implode("\n", $output));
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

?>
