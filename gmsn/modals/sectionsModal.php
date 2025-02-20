

<style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f4;
    }

    .form-control {
        border: none;
        border-radius: 0;
        border-bottom: 1px solid #ced4da;
        box-shadow: none;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #1a237e; 
        box-shadow: 0 0 0 0.25rem rgba(26, 35, 126, 0.25); 
    }

    .header h1 {
        margin: 0;
        font-size: 32px;
        font-weight: bold;
    }

    .header p {
        margin-top: 10px;
        font-size: 18px;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
    }

    fieldset {
        border: 2px solid #1a237e; 
        border-radius: 10px;
        margin-bottom: 20px;
        padding: 20px;
    }

    legend {
        font-size: 24px;
        font-weight: bold;
        color: #1a237e; 
        border-bottom: 2px solid #1a237e; 
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: #1a237e; 
        border-color: #1a237e; 
    }

    .btn-primary:hover {
        background-color: #0d47a1; 
        border-color: #0d47a1;
    }



    .btn-secondary:hover {
        background-color: #bdbdbd; 
        border-color: #bdbdbd;
    }
</style>


<!-- Modal Form for adding section in elementary-->
<div class="modal fade" id="addSectionModalElem" tabindex="-1" role="dialog" aria-labelledby="addSectionModalElemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="save_section.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                    <legend class="mb-4">Create Elementary Section</legend>
                    <input type="hidden" name="deptID" value=1>
                        <?php 
                            require_once("includes/config.php");
                            $query = "SELECT ay.*, s.semName FROM academic_year ay
                            JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                            foreach ($acadYear as $row): 
                        ?>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="txtAyName" readonly value="<?php echo $row['ayName']; ?>" required>
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                    <div class="invalid-feedback">Please select an academic year.</div>
                                </div>
                            </div>
                            <?php endforeach;?>
                            <div class="col-md-6 mb-3">
                                <label for="gradelvl" class="form-label fw-bold">Grade Level:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control" id="gradelvl" name="selgradelvl" required>
                                        <option selected disabled value>Select Grade Level</option>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                            $query = "SELECT * FROM grade_level WHERE isActive=1 AND gradelvl IN ('Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6')";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['gradelvlID'] . '">' . $row['gradelvl'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching subjects</option>';
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a grade level.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="section" class="form-label fw-bold">Section:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="section" name="txtsection" placeholder="Enter Section" required>
                                    <div class="invalid-feedback">Input a section name.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="selAdv" class="form-label fw-bold">Adviser:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" data-live-search="true" id="selAdv" name="selAdv" required>
                                        <option selected disabled value>Select Adviser</option>
                                        <?php
                                            require_once("includes/config.php");
                                            require_once("fetch/fetch_activeAY.php");
                                            try {

                                                // Query to fetch all active faculty who are not assigned to any section in the current academic year
                                                $query = "
                                                    SELECT f.facultyID, f.lname, f.fname, f.mname
                                                    FROM faculty f
                                                    LEFT JOIN sections s ON f.facultyID = s.facultyID AND s.isActive = 1 AND s.ayName = :currentAyID
                                                    WHERE f.isActive = 1 AND f.userTypeID = 2 AND s.facultyID IS NULL
                                                    ORDER BY f.lname ASC, f.fname ASC, f.mname ASC
                                                ";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindParam(':currentAyID', $activeAY, PDO::PARAM_STR);
                                                $stmt->execute();

                                                $count = 1;

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $lname = ucwords(strtolower($row['lname']));
                                                    $fname = ucwords(strtolower($row['fname']));
                                                    $mname = ucwords(strtolower($row['mname']));
                                                    echo '<option value="' . $row['facultyID'] . '">' . $count++ . ". " . $lname . ', ' . $fname . ' ' . $mname . '</option>';                                                                                                    }
                                            } catch (PDOException $e) {
                                                echo '<option disabled>Error fetching advisers</option>';
                                            }
                                        ?>
                                    </select>
                                
                                    <div class="invalid-feedback">Input a section name.</div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveSectionsBtn">
                            <i class="bi bi-save"></i> Save
                        </button>
                        <button type="button" class="btn btn-secondary" name="cancelBtn" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                    </div>
                </form>        
            </div>
        </div>
    </div>
</div>

<!-- Modal Form for adding section in JHS-->
<div class="modal fade" id="addSectionModalJHS" tabindex="-1" role="dialog" aria-labelledby="addSectionModalJHSLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="save_section.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                    <legend class="mb-4">Create JHS Section</legend>
                    <input type="hidden" name="deptID" value=2>
                        <?php 
                            require_once("includes/config.php");
                            $query = "SELECT ay.*, s.semName FROM academic_year ay
                            JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                            foreach ($acadYear as $row): 
                        ?>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="txtAyName" readonly value="<?php echo $row['ayName']; ?>" required>
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                    <div class="invalid-feedback">Please select an academic year.</div>
                                </div>
                            </div>
                            <?php endforeach;?>
                            <div class="col-md-6 mb-3">
                                <label for="gradelvl" class="form-label fw-bold">Grade Level:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control" id="gradelvl" name="selgradelvl" required>
                                        <option selected disabled value>Select Grade Level</option>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                                $query = "SELECT * FROM grade_level WHERE isActive=1 AND gradelvl IN ('Grade 7', 'Grade 8', 'Grade 9', 'Grade 10')";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['gradelvlID'] . '">' . $row['gradelvl'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching subjects</option>';
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a grade level.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="section" class="form-label fw-bold">Section:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="section" name="txtsection" placeholder="Enter Section" required>
                                    <div class="invalid-feedback">Input a section name.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="selAdv" class="form-label fw-bold">Adviser:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" data-live-search="true" id="selAdv" name="selAdv" required>
                                        <option selected disabled value>Select Adviser</option>
                                        <?php
                                            require_once("../includes/config.php");
                                            try {

                                                // Query to fetch all active faculty who are not assigned to any section in the current academic year
                                                $query = "
                                                    SELECT f.facultyID, f.lname, f.fname, f.mname
                                                    FROM faculty f
                                                    LEFT JOIN sections s ON f.facultyID = s.facultyID AND s.isActive = 1 AND s.ayName = :currentAyID
                                                    WHERE f.isActive = 1 AND f.userTypeID = 2 AND s.facultyID IS NULL
                                                    ORDER BY f.lname ASC, f.fname ASC, f.mname ASC
                                                ";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindParam(':currentAyID', $activeAY, PDO::PARAM_STR);
                                                $stmt->execute();

                                                $count = 1;

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $lname = ucwords(strtolower($row['lname']));
                                                    $fname = ucwords(strtolower($row['fname']));
                                                    $mname = ucwords(strtolower($row['mname']));
                                                    echo '<option value="' . $row['facultyID'] . '">' . $count++ . ". " . $lname . ', ' . $fname . ' ' . $mname . '</option>';                                                                                                    }
                                            } catch (PDOException $e) {
                                                echo '<option disabled>Error fetching advisers</option>';
                                            }
                                        ?>
                                    </select>                                    
                                    <div class="invalid-feedback">Input a section name.</div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveSectionsBtn">
                            <i class="bi bi-save"></i> Save
                        </button>
                        <button type="button" class="btn btn-secondary" name="cancelBtn" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                    </div>
                </form>        
            </div>
        </div>
    </div>
</div>

<!-- Modal Form SHS-->
<div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="save_section.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Create SHS Section</legend>
                        <?php 
                            require_once("includes/config.php");
                            $query = "SELECT ay.*, s.semName FROM academic_year ay
                            JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
                            $stmt = $conn->prepare($query);
                            $stmt->execute();
                            $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                            foreach ($acadYear as $row): 
                        ?>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="txtAyName" readonly value="<?php echo $row['ayName']; ?>" required>
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                    <input type="hidden" name="deptID" value=3>
                                    <input type="hidden" name="tabURL" value="<?php echo isset($_GET['tab']) ? htmlspecialchars($_GET['tab']) : ''; ?>">
                                    <div class="invalid-feedback">Please select an academic year.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selsem" name="selSem" readonly value="<?php echo $row['semName']; ?>" required>
                                    <input type="hidden" name="selSem" value="<?php echo $row['semID']; ?>">
                                    <div class="invalid-feedback">Please select a term.</div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="gradelvl" class="form-label fw-bold">Grade Level:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control" id="gradelvl" name="selgradelvl" required>
                                        <option selected disabled value>Select Grade Level</option>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                            $query = "SELECT * FROM grade_level WHERE isActive=1 AND gradelvl IN ('Grade 11', 'Grade 12')";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['gradelvlID'] . '">' . $row['gradelvl'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching subjects</option>';
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a grade level.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selpro" class="form-label fw-bold">Program:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-book"></i></span>
                                    </div>
                                    <select class="form-select form-control" id="selpro" name="selProg" required>
                                        <option selected disabled value>Select Program</option>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                            $query = "SELECT * FROM programs 
                                            WHERE isActive=1 
                                            AND programcode NOT IN ('Elem/JHS') 
                                            ORDER BY programcode ASC";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['programID'] . '">' . $row['programcode'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching Programs</option>';
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a program.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="section" class="form-label fw-bold">Section:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="section" name="txtsection" placeholder="Enter Section" required>
                                    <div class="invalid-feedback">Input a section name.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selAdv" class="form-label fw-bold">Adviser:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" data-live-search="true" id="selAdv" name="selAdv" required>
                                        <option selected disabled value>Select Adviser</option>
                                        <?php
                                            require_once("../includes/config.php");
                                            try {

                                                // Query to fetch all active faculty who are not assigned to any section in the current academic year
                                                $query = "
                                                    SELECT f.facultyID, f.lname, f.fname, f.mname
                                                    FROM faculty f
                                                    LEFT JOIN sections s ON f.facultyID = s.facultyID 
                                                    AND s.isActive = 1 
                                                    AND s.ayName = :currentAyID 
                                                    AND (s.semID = :currentSemID OR s.semID IS NULL)
                                                    WHERE f.isActive = 1 AND f.userTypeID = 2 AND s.facultyID IS NULL
                                                    ORDER BY f.lname ASC, f.fname ASC, f.mname ASC
                                                ";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindParam(':currentAyID', $activeAY, PDO::PARAM_STR);
                                                $stmt->bindParam(':currentSemID', $activeSem, PDO::PARAM_INT);
                                                $stmt->execute();

                                                $count = 1;

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $lname = ucwords(strtolower($row['lname']));
                                                    $fname = ucwords(strtolower($row['fname']));
                                                    $mname = ucwords(strtolower($row['mname']));
                                                    echo '<option value="' . $row['facultyID'] . '">' . $count++ . ". " . $lname . ', ' . $fname . ' ' . $mname . '</option>';                                                                                                    }
                                            } catch (PDOException $e) {
                                                echo '<option disabled>Error fetching advisers</option>';
                                            }
                                        ?>
                                    </select>                                    
                                    <div class="invalid-feedback">Input a section name.</div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveSectionsBtn">
                            <i class="bi bi-save"></i> Save
                        </button>
                        <button type="button" class="btn btn-secondary" name="cancelBtn" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                    </div>
                </form>    
            </div>
        </div>
    </div>
</div>


<!-- UPDATE Form for ELEM-->
<div class="modal fade" id="updateSectionModalElem" tabindex="-1" role="dialog" aria-labelledby="updateSectionModalElemLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="save_section.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <input type="hidden" name="sectionID" id="sectionIDelem">
                        <legend class="mb-4">Update Section</legend>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="updateAY" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                                    </div>
                                    <?php 
                                            require_once("includes/config.php");
                                            $query = "SELECT ay.*,s.semName FROM academic_year ay
                                            JOIN semester s ON ay.semID = s.semID";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                            foreach ($acadYear as $row): 
                                                endforeach
                                        ?>
                                    <input type="text" class="form-control" id="readOnlyAYelem" name="" readonly>
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updategradelvl" class="form-label fw-bold">Grade Level:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control" id="updategradelvlElem" name="selgradelvl" disabled>
                                        <option selected disabled>Grade Level</option>
                                    <?php
                                    require_once("./includes/config.php");
                                    try {
                                        $query = "SELECT * FROM grade_level WHERE isActive=1 AND gradelvl IN ('grade 1', 'grade 2', 'grade 3', 'grade 4', 'grade 5', 'grade 6')";
                                        $stmt = $conn->query($query);

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row['gradelvlID'] . '">' . $row['gradelvl'] . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<option disabled>Error fetching subjects</option>';
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="updatesection" class="form-label fw-bold">Section:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="updatesectionElem" name="txtsection" placeholder="Enter Section">
                                </div>
                            </div>
                        </div>
                        <div class="row b-4">
                            <div class="col-md-12 mb-3">
                                <label for="updateselAdv" class="form-label fw-bold">Adviser:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" data-live-search="true" id="updateselAdvElem" name="selAdv" >
                                        <option selected disabled value>Select Adviser</option>
                                        <?php
                                            require_once("../includes/config.php");
                                            try {

                                                // Query to fetch all active faculty who are not assigned to any section in the current academic year
                                                $query = "
                                                    SELECT f.facultyID, f.lname, f.fname, f.mname
                                                    FROM faculty f
                                                    LEFT JOIN sections s ON f.facultyID = s.facultyID 
                                                    AND s.isActive = 1 
                                                    AND s.ayName = :currentAyID 
                                                    AND (s.semID = :currentSemID OR s.semID IS NULL)
                                                    WHERE f.isActive = 1 AND f.userTypeID = 2 AND s.facultyID IS NULL
                                                    ORDER BY f.lname ASC, f.fname ASC, f.mname ASC
                                                ";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindParam(':currentAyID', $activeAY, PDO::PARAM_STR);
                                                $stmt->bindParam(':currentSemID', $activeSem, PDO::PARAM_INT);
                                                $stmt->execute();

                                                $count = 1;

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $lname = ucwords(strtolower($row['lname']));
                                                    $fname = ucwords(strtolower($row['fname']));
                                                    $mname = ucwords(strtolower($row['mname']));
                                                    echo '<option value="' . $row['facultyID'] . '">' . $count++ . ". " . $lname . ', ' . $fname . ' ' . $mname . '</option>';                                                                                                    }
                                            } catch (PDOException $e) {
                                                echo '<option disabled>Error fetching advisers</option>';
                                            }
                                        ?>
                                    </select>                                    
                                    <div class="invalid-feedback">Input a section name.</div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateSectionsBtn">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <button type="button" class="btn btn-secondary" name="cancelBtn" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                    </div>
                </form>                            
            </div>
        </div>
    </div>
</div>
<!-- UPDATE Form for JHS-->
<div class="modal fade" id="updateSectionModalJHS" tabindex="-1" role="dialog" aria-labelledby="updateSectionModalJHSLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="save_section.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <input type="hidden" name="sectionID" id="sectionIDup">
                        <legend class="mb-4">Update Section</legend>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="updateAY" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                                    </div>
                                    <?php 
                                            require_once("includes/config.php");
                                            $query = "SELECT ay.*,s.semName FROM academic_year ay
                                            JOIN semester s ON ay.semID = s.semID";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                            foreach ($acadYear as $row): 
                                                endforeach
                                        ?>
                                                    
                                    <input type="text" class="form-control" id="readOnlyAYjhs" name="updateSelAy" readonly>
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updategradelvl" class="form-label fw-bold">Grade Level:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control" id="updategradelvlup" name="selgradelvl" disabled>
                                        <option selected disabled>Grade Level</option>
                                    <?php
                                    require_once("./includes/config.php");
                                    try {
                                        $query = "SELECT * FROM grade_level WHERE isActive=1 AND gradelvl IN ('grade 7', 'grade 8', 'grade 9', 'grade 10')";
                                        $stmt = $conn->query($query);

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row['gradelvlID'] . '">' . $row['gradelvl'] . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<option disabled>Error fetching subjects</option>';
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="updatesection" class="form-label fw-bold">Section:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="updatesectionup" name="txtsection" placeholder="Enter Section">
                                </div>
                            </div>
                        </div>
                        <div class="row b-4">
                            <div class="col-md-12 mb-3">
                                <label for="updateselAdv" class="form-label fw-bold">Adviser:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" data-live-search="true" id="updateselAdvup" name="selAdv">
                                        <option selected disabled value>Select Adviser</option>
                                        <?php
                                            require_once("../includes/config.php");
                                            try {

                                                // Query to fetch all active faculty who are not assigned to any section in the current academic year
                                                $query = "
                                                    SELECT f.facultyID, f.lname, f.fname, f.mname
                                                    FROM faculty f
                                                    LEFT JOIN sections s ON f.facultyID = s.facultyID 
                                                    AND s.isActive = 1 
                                                    AND s.ayName = :currentAyID 
                                                    AND (s.semID = :currentSemID OR s.semID IS NULL)
                                                    WHERE f.isActive = 1 AND f.userTypeID = 2 AND s.facultyID IS NULL
                                                    ORDER BY f.lname ASC, f.fname ASC, f.mname ASC
                                                ";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindParam(':currentAyID', $activeAY, PDO::PARAM_STR);
                                                $stmt->bindParam(':currentSemID', $activeSem, PDO::PARAM_INT);
                                                $stmt->execute();

                                                $count = 1;

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $lname = ucwords(strtolower($row['lname']));
                                                    $fname = ucwords(strtolower($row['fname']));
                                                    $mname = ucwords(strtolower($row['mname']));
                                                    echo '<option value="' . $row['facultyID'] . '">' . $count++ . ". " . $lname . ', ' . $fname . ' ' . $mname . '</option>';                                                                                                    }
                                            } catch (PDOException $e) {
                                                echo '<option disabled>Error fetching advisers</option>';
                                            }
                                        ?>
                                    </select>                                    
                                    <div class="invalid-feedback">Input a section name.</div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateSectionsBtn">
                            <i class="bi bi-save"></i> Update
                        </button>
                        <button type="button" class="btn btn-secondary" name="cancelBtn" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                    </div>
                </form>                            
            </div>
        </div>
    </div>
</div>
<!-- UPDATE Form SHS -->
<div class="modal fade" id="updateSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <form action="save_section.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <fieldset class="border p-4 rounded mb-4">
                <input type="hidden" name="sectionID" id="sectionIDshs">
                <legend class="mb-4">Update Section</legend>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label for="updateAY" class="form-label fw-bold">Academic Year:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                            </div>
                            <?php 
                                    require_once("includes/config.php");
                                    $query = "SELECT ay.*,s.semName FROM academic_year ay
                                    JOIN semester s ON ay.semID = s.semID";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                    foreach ($acadYear as $row): 
                                        endforeach
                                ?>
                            <input type="text" class="form-control" id="updateSelAy" name="updateSelAy" readonly >
                            <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="updatesem" class="form-label fw-bold">Term: </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                </div>
                                <input type="text" class="form-control" name="" readonly id="readOnlySem">
                                <input type="hidden" name="selSem" id="Sem">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="updateprogram" class="form-label fw-bold">Program:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-book"></i></span>
                                </div>
                                <select class="form-select form-control " id="updateprogram" name="selProg" disabled>
                                <option selected disabled>Select Program</option>
                                <?php
                                try {
                                    $query = "SELECT * 
                                                FROM programs WHERE isActive=1 
                                                AND programID != 54";

                                    $stmt = $conn->query($query);

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row['programID'] . '">' . $row['programcode'] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    echo '<option disabled>Error fetching Programs</option>';
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="updategradelvl" class="form-label fw-bold">Grade Level:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                </div>
                                <select class="form-select form-control " id="updategradelvl" name="selgradelvl" disabled>
                            <option selected disabled>Grade Level</option>
                        <?php
                        require_once("./includes/config.php");
                        try {
                            // Prepare a SQL query to retrieve subjects
                            $query = "SELECT * FROM grade_level WHERE isActive=1 AND gradelvl IN ('Grade 11', 'Grade 12')";

                            // Execute the query
                            $stmt = $conn->query($query);

                            // Fetch subjects and populate the select dropdown
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . $row['gradelvlID'] . '">' . $row['gradelvl'] . '</option>';
                            }
                        } catch (PDOException $e) {
                            // Handle database connection errors
                            echo '<option disabled>Error fetching subjects</option>';
                        }
                        ?>
                        </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="updatesection" class="form-label fw-bold">Section:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                </div>
                                <input type="text" class="form-control" id="updatesection" name="txtsection" placeholder="Enter Section">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="updateselAdv" class="form-label fw-bold">Adviser:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                </div>
                                <select class="form-select form-control selectpicker" data-live-search="true" id="updateselAdv" name="selAdv">
                                    <option selected disabled value>Select Adviser</option>
                                    <?php
                                            require_once("../includes/config.php");
                                            try {

                                                // Query to fetch all active faculty who are not assigned to any section in the current academic year
                                                $query = "
                                                    SELECT f.facultyID, f.lname, f.fname, f.mname
                                                    FROM faculty f
                                                    LEFT JOIN sections s ON f.facultyID = s.facultyID 
                                                    AND s.isActive = 1 
                                                    AND s.ayName = :currentAyID 
                                                    AND (s.semID = :currentSemID OR s.semID IS NULL)
                                                    WHERE f.isActive = 1 AND f.userTypeID = 2 AND s.facultyID IS NULL
                                                    ORDER BY f.lname ASC, f.fname ASC, f.mname ASC
                                                ";
                                                $stmt = $conn->prepare($query);
                                                $stmt->bindParam(':currentAyID', $activeAY, PDO::PARAM_STR);
                                                $stmt->bindParam(':currentSemID', $activeSem, PDO::PARAM_INT);
                                                $stmt->execute();

                                                $count = 1;

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $lname = ucwords(strtolower($row['lname']));
                                                    $fname = ucwords(strtolower($row['fname']));
                                                    $mname = ucwords(strtolower($row['mname']));
                                                    echo '<option value="' . $row['facultyID'] . '">' . $count++ . ". " . $lname . ', ' . $fname . ' ' . $mname . '</option>';                                                                                                    }
                                            } catch (PDOException $e) {
                                                echo '<option disabled>Error fetching advisers</option>';
                                            }
                                        ?>
                                </select>                                    
                                <div class="invalid-feedback">Input a section name.</div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary me-md-2" name="updateSectionsBtn">
                        <i class="bi bi-save"></i> Update
                    </button>
                    <button type="button" class="btn btn-secondary" name="cancelBtn" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Cancel
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>


<script>
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})();

$(document).ready(function(){
    $('.update-btn-shs').click(function(){
        // Get data attributes from the clicked button
        var sectionID = $(this).data('section-id');
        var secName = $(this).data('section-name');
        var ay = $(this).data('section-ay');
        var program = $(this).data('section-program');
        var sem = $(this).data('section-sem');
        var gradelvl = $(this).data('section-gradelvl');
        var adviser = $(this).data('section-adviser');
        var ayName = $(this).data('ay-Name');
        var semName = $(this).data('sem-Name');
        var semID = $(this).data('sem-id');

        // Set the values of input fields
        $('#shsSecID').val(sectionID);
        $('#sectionIDshs').val(sectionID);
        $('#updatesection').val(secName);

        // Set selected options for dropdowns
        $('#updateAY').val(ay).trigger('change');
        $('#updateprogram').val(program).trigger('change');
        $('#updatesem').val(sem).trigger('change');
        $('#updategradelvl').val(gradelvl).trigger('change');
        $('#updateselAdv').val(adviser).trigger('change');
        $('#updateSelAy').val(ayName).trigger('change');
        $('#readOnlySem').val(semName).trigger('change');
        $('#Sem').val(semID).trigger('change');
    });

    $('.update-btn-jhs').click(function(){
        var sectionID = $(this).data('section-id');
        var secName = $(this).data('section-name');
        var ay = $(this).data('section-ay');
        var gradelvl = $(this).data('section-gradelvl');
        var adviser = $(this).data('faculty-id');
        var ayName = $(this).data('ay-name');

        $('#sectionIDup').val(sectionID);
        $('#updatesectionup').val(secName);
        $('#readOnlyAYjhs').val(ayName);
        
        $('#updategradelvlup option, #updateselAdvup option').each(function() {
            if ($(this).val() == gradelvl || $(this).val() == adviser) {
                $(this).attr('selected', 'selected');
            }
        });

        $('#updategradelvlup, #updateselAdvup').trigger('change');
    });
    $('.update-btn-elem').click(function(){
        var sectionID = $(this).data('section-id');
        var secName = $(this).data('section-name');
        var ay = $(this).data('section-ay');
        var gradelvl = $(this).data('section-gradelvl');
        var adviser = $(this).data('faculty-id');
        var ayName = $(this).data('ay-name');


        $('#sectionIDelem').val(sectionID);
        $('#updatesectionElem').val(secName);

        $('#readOnlyAYelem').val(ayName);

        // Set the value for #updategradelvlElem and #updateselAdvElem
        $('#updategradelvlElem').val(gradelvl).trigger('change');
        $('#updateselAdvElem').val(adviser).trigger('change');


        $('#upselsub').val(subjectname);

        
});

});
</script>



<script>
    function handleDeleteButtonClick(secID, secName, program, rowElement) {
        Swal.fire({
            title: 'Confirmation',
            text: 'You are about to delete the section: ' + program + ' ' + secName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('delete_section.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'secID=' + encodeURIComponent(secID)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The section ' + secName + ' has been deleted successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Remove the row from the table without reloading the page
                            rowElement.remove();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Deletion Failed',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete the section. Please try again later.'
                    });
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); 
                var program = this.getAttribute('data-section-program');
                var secID = this.getAttribute('data-section-id');
                var secName = this.closest('tr').querySelector('td:nth-child(5)').innerText; 
                var rowElement = this.closest('tr'); // Get the row element
                handleDeleteButtonClick(secID, secName, program, rowElement);
            });
        });
    });
</script>
<script>
    function handleInactiveButtonClick(secID, secName, program, rowElement) {
        Swal.fire({
            title: 'Confirmation Required',
            html: 'You are about to move the <strong>' + (program || '') + '</strong> <strong>' + secName + '</strong> to inactive. This action can be undone later.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Move'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('inactivateSec.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'secID=' + encodeURIComponent(secID)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Moved Successfully!',
                            text: 'The section ' + secName + ' has been moved to inactive.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            rowElement.remove();

                            setTimeout(function() {
                                location.reload(); 
                            }, 500);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Moving Failed',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'An Error Occurred',
                        text: 'There was a problem moving the section. Please try again later.'
                    });
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.inactive-btn').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault(); 
            var program = this.getAttribute('data-inactive-program');
            var secID = this.getAttribute('data-inactive-id');
            var secName = this.closest('tr').querySelector('td:nth-child(' + (program ? '5' : '3') + ')').innerText;
            var rowElement = this.closest('tr'); 
            handleInactiveButtonClick(secID, secName, program, rowElement);
        });
    });
});

</script>

