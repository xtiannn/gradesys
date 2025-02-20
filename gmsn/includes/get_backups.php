<?php
$backupDir = '../../backup/';

if (!is_dir($backupDir)) {
    echo json_encode([]);
    exit;
}

$backupFiles = array_diff(scandir($backupDir), array('..', '.'));

$backupData = [];

foreach ($backupFiles as $file) {
    $filePath = $backupDir . $file;

    if (is_file($filePath)) {
        $backupData[] = [
            'name' => $file,
            'date' => filemtime($filePath), 
        ];
    }
}

// Sort backups by date, latest first
usort($backupData, function($a, $b) {
    return $b['date'] <=> $a['date']; 
});

// Only keep the latest backup
if (!empty($backupData)) {
    $latestBackup = $backupData[0];
    $latestBackup['date'] = date("Y-m-d H:i:s", $latestBackup['date']); 
    $response = [$latestBackup];
} else {
    $response = [];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
