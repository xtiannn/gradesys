    <?php 
    require_once "../includes/config.php";

    $sqlSecName = "SELECT 
                    s.secID,
                    s.secName, 
                    gl.gradelvlcode, 
                    p.programcode
                FROM 
                    sections s
                JOIN 
                    grade_level gl ON s.gradelvlID = gl.gradelvlID
                LEFT JOIN 
                    programs p ON s.programID = p.programID
                WHERE 
                    s.secID = :secID
                    AND s.gradelvlID = :gradelvlID
                    AND (s.programID = :programID OR s.programID IS NULL)";

    $stmtSecName = $conn->prepare($sqlSecName);

    // Bind parameters
    $stmtSecName->bindParam(':secID', $secID, PDO::PARAM_INT);
    $stmtSecName->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
    $stmtSecName->bindParam(':programID', $programID, PDO::PARAM_INT);

    // Execute the statement
    $stmtSecName->execute();
    $sec = $stmtSecName->fetch(PDO::FETCH_ASSOC);
    
    $sectionname = ucwords(strtolower(trim( $sec['secName'])));
    $secID = $sec['secID'];
    $programname = $sec['programcode'];
    $gradelvlname = $sec['gradelvlcode'];


    $secName = "$programname $gradelvlname - $sectionname";

?>