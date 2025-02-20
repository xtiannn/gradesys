<?php 
require_once("includes/config.php");

if (isset($_POST['updateAYBtn'])) {
    $ayID = $_POST['ayID'];
    $ayName = $_POST['txtAYName'];
    $sem = $_POST['selSem'];
    $startYear = $_POST['startYear'];
    $endYear = $_POST['endYear'];

    try {
        // Fetch the current academic year details
        $query = "SELECT semID, ayName, start, end FROM academic_year WHERE ayID = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$ayID]);
        $currentAY = $stmt->fetch(PDO::FETCH_ASSOC);

        // Prepare for update
        $updatedFields = [
            'ayName' => $ayName,
            'semID' => $sem,
            'start' => $startYear,
            'end' => $endYear,
        ];

        $changesMade = false;

        // Check if any fields have changed
        foreach ($updatedFields as $field => $value) {
            if ($currentAY[$field] != $value) {
                $changesMade = true;
                break; // Exit loop if any field differs
            }
        }

        // Update the database only if changes were made
        if ($changesMade) {
            $query = "UPDATE academic_year SET ayName = ?, semID = ?, start = ?, end = ? WHERE ayID = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$ayName, $sem, $startYear, $endYear, $ayID]);

            // Check if only the semester changed
            if ($currentAY['semID'] != $sem && $currentAY['ayName'] == $ayName && $currentAY['start'] == $startYear && $currentAY['end'] == $endYear) {
                $referrer = $_SERVER['HTTP_REFERER']; 
                header("Location: $referrer?alert=sem-changed"); 
            } else {
                $referrer = $_SERVER['HTTP_REFERER']; 
                header("Location: $referrer?alert=success"); 
            }
        } else {
            $referrer = $_SERVER['HTTP_REFERER']; 
            header("Location: $referrer?alert=no-changes"); 
        }

        exit; 

    } catch (PDOException $e) {
        // Handle database error
        $referrer = $_SERVER['HTTP_REFERER']; 
        header("Location: $referrer?alert=db_error&message=" . urlencode($e->getMessage())); 
        exit; 
    }
}
?>
