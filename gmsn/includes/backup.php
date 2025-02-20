<?php
session_start(); 
require_once "config.php"; 

if (!$conn) {
    die("Connection failed: " . $conn->errorInfo()[2]);
}

// Get the current timestamp
$timestamp = date('Y-m-d_H-i-s');  // Format: YYYY-MM-DD_HH-MM-SS
$backupName = 'GMSN_backup_' . $timestamp; // Concatenate timestamp

// Sanitize backup name
$backupName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $backupName); 

$tables = [];
$result = $conn->query("SHOW TABLES");
$tables = $result->fetchAll(PDO::FETCH_COLUMN);

$sqlDump = '';
foreach ($tables as $table) {
    $stmt = $conn->query("SHOW CREATE TABLE `$table`");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (isset($row['Create Table'])) {
        $sqlDump .= "\n\n" . $row['Create Table'] . ";\n\n"; 
    } elseif (isset($row['create_table'])) { 
        $sqlDump .= "\n\n" . $row['create_table'] . ";\n\n"; 
    } else {
        error_log("CREATE TABLE statement not found for $table.");
        continue; // Skip this table
    }

    $stmt = $conn->query("SELECT * FROM `$table`");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sqlDump .= "INSERT INTO `$table` (`" . implode('`, `', array_keys($row)) . "`) VALUES ('" . implode("', '", array_map('addslashes', array_values($row))) . "');\n";
    }
}

$backupFolder = '../../backup/'; 
if (!is_dir($backupFolder)) {
    mkdir($backupFolder, 0755, true); 
}

$backupFileName = $backupFolder . $backupName . '.sql'; 
if (file_put_contents($backupFileName, $sqlDump) === false) {
    error_log("Error writing backup file $backupFileName.");
    $redirectUrl = htmlspecialchars($_SERVER['HTTP_REFERER']) . '?backupstatus=failed';
    header("Location: $redirectUrl");
    exit; 
}

$_SESSION['backup_file'] = $backupFileName;

$redirectUrl = htmlspecialchars($_SERVER['HTTP_REFERER']) . '?backupstatus=success';
header("Location: $redirectUrl");
$conn = null; 
exit; 
?>
