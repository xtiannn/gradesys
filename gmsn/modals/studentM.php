
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
        border-color: #1a237e; /* Navy Blue Focus Color */
        box-shadow: 0 0 0 0.25rem rgba(26, 35, 126, 0.25); /* Navy Blue Shadow on Focus */
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
        border: 2px solid #1a237e; /* Navy Blue Border */
        border-radius: 10px;
        margin-bottom: 20px;
        padding: 20px;
    }

    legend {
        font-size: 24px;
        font-weight: bold;
        color: #1a237e; /* Navy Blue Text */
        border-bottom: 2px solid #1a237e; /* Navy Blue Bottom Border */
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: #1a237e; /* Navy Blue Button Color */
        border-color: #1a237e; /* Navy Blue Border Color */
    }

    .btn-primary:hover {
        background-color: #0d47a1; /* Darker Navy Blue on Hover */
        border-color: #0d47a1;
    }



    .btn-secondary:hover {
        background-color: #bdbdbd; /* Darker Gray on Hover */
        border-color: #bdbdbd;
    }
    .text-primary {
    font-weight: bold;
}
.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5em 1em;
    margin: 0.25em 0;
    border-radius: 999px;
}

.btn-close {
    margin-left: 0.5em;
    cursor: pointer;
}

.modal-custom{
    max-width: 90%; 
}
</style>


<?php 
    require_once("includes/config.php");
    $query = "SELECT ay.*, s.semName FROM academic_year ay
    JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($acadYear as $row): 
        endforeach 
?>
<!-- Modal Form subject student-->
<div class="modal fade" id="addStudent" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <form action="modals/enroll_student.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Enroll Students</legend>
                        <input type="hidden" name="subjectID" value="<?php echo $subID?>">
                        <input type="hidden" name="programID" value="<?php echo $progID?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID?>">
                        <input type="hidden" name="facultyID" value="<?php echo $facultyID?>">
                        <input type="hidden" name="secID" value="<?php echo $secID?>">
                        <input type="hidden" name="facultyName" value="<?php echo $facultyName?>">
                        <input type="hidden" name="secName" value="<?php echo $secName?>">
                        <input type="hidden" name="subjectName" value="<?php echo $subjectName?>">
                        <input type="hidden" name="semID" value="<?php echo $semID?>">
                        <input type="hidden" name="deptID" value="<?php echo $deptID?>">
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="" readonly value="<?php echo $row['ayName']; ?>">
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                </div>
                                    <input type="text" class="form-control" id="selsem" name="" readonly value="<?php echo $row['semName']; ?>">
                                    <input type="hidden" name="selSem" value="<?php echo $row['semID']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selstudSHS" class="form-label fw-bold">Select Student:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" multiple data-live-search="true" id="selstudSHS" name="selStud[]" required>
                                       <?php
                                        include("../includes/config.php");
                                            try {
                                                $sql = "SELECT * FROM students WHERE isActive = 1 
                                                AND studID NOT IN (SELECT studID FROM section_students WHERE secID = :secID AND subjectID = :subID)";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindParam(':secID', $secID);
                                                $stmt->bindParam(':subID', $subID);
                                                $stmt->execute();
                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        $studentName = $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'];
                                                        $studentLRN = $row['lrn'];
                                                        echo '<option value="' . $row['studID'] . '" data-subtext="LRN : ' .$studentLRN . '">'
                                                         . $studentName .'</option>';
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
                            <div id="selectedStudSHS" class="fw-bold">
                                
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="enrollBtn">
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const selStud = document.getElementById('selstudSHS');
    const selectedStud = document.getElementById('selectedStudSHS');

    function updateSelectedStudents() {
        // Clear existing chips
        selectedStud.innerHTML = '';

        // Create chips for each selected option
        Array.from(selStud.selectedOptions).forEach(option => {
            const chip = document.createElement('span');
            chip.className = 'badge bg-primary me-2';
            chip.innerText = option.text;
            chip.setAttribute('data-value', option.value);

            const closeButton = document.createElement('button');
            closeButton.className = 'btn-close btn-close-white ms-1';
            closeButton.setAttribute('aria-label', 'Close');
            closeButton.addEventListener('click', function (e) {
                e.preventDefault();
                option.selected = false;
                $(selStud).selectpicker('refresh');
                updateSelectedStudents();
            });

            chip.appendChild(closeButton);
            selectedStud.appendChild(chip);
        });

    }

    // Initial update
    updateSelectedStudents();

    // Update on change
    selStud.addEventListener('change', updateSelectedStudents);
});

</script>



<!-- Modal Form section student-->
<div class="modal fade" id="addStudentSec" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <form action="modals/enroll_studentSec.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Enroll Students</legend>
                        <input type="hidden" id="programID" name="programID" value="<?php echo $programID?>">
                        <input type="hidden" id="gradelvlID" name="gradelvlID" value="<?php echo $gradelvlID?>">
                        <input type="hidden" id="secIDenroll" name="secID" value="<?php echo $secID?>">
                        <input type="hidden" id="secNameenroll" name="secName" value="<?php echo $sectionName?>">
                        <input type="hidden" id="facultyIDenroll" name="facultyID" value="<?php echo $facultyID?>">
                        <input type="hidden" id="deptIDenroll" name="deptID" value="<?php echo $deptID?>">
                        <input type="hidden" id="ayName" name="ayName" value="<?php echo $secAyName?>">


                        <div class="row">
                        <div class="col-md-7">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="studentTable">                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">LRN</th>
                                                <th>Student Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" name="selAY" value="<?php echo $secAyName; ?>">
                            <input type="hidden" name="selSem" value="<?php echo $secSem; ?>">


                            <div class="col-md-5">
                                <!-- Add your form fields for the left column here -->
                                <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-people"></i></span>
                                        </div>
                                        <select class="form-select form-control selectpicker" multiple data-live-search="true" id="selstud" name="selStud[]" required>
                                        <?php
                                            include("../includes/config.php");
                                                try {
                                                    if($deptID == 3){
                                                        $sql = "SELECT * FROM students 
                                                            WHERE isActive = 1 
                                                            AND studID NOT IN 
                                                            (SELECT DISTINCT studID FROM section_students WHERE ayName = :ayName AND (semID = :semID OR semID IS NULL) AND (adviserID IS NULL))
                                                            ORDER BY lname ASC";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->bindParam(':ayName', $secAyName, PDO::PARAM_STR);
                                                        $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                                                        $stmt->execute();
                                                    }else{
                                                        $sql = "SELECT * FROM students 
                                                            WHERE isActive = 1 
                                                            AND studID NOT IN 
                                                            (SELECT DISTINCT studID FROM section_students WHERE ayName = :ayName AND adviserID IS NULL)
                                                            ORDER BY lname ASC";
                                                        $stmt = $conn->prepare($sql);
                                                        $stmt->bindParam(':ayName', $secAyName, PDO::PARAM_STR);
                                                        $stmt->execute();
                                                    }
                                                    // Output the select options
                                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        $studentLRN = $row['lrn'];
                                                        echo '<option value="' . $row['studID'] . '" data-subtext="LRN : '.$studentLRN .'">' . ucwords(strtolower($row['lname'])) .', '.ucwords(strtolower($row['fname'])).' '.ucwords(strtolower($row['mname'])). '</option>';

                                                    }
                                                } catch (PDOException $e) {
                                                    // Handle any errors that occur during query execution
                                                    echo '<option disabled>Error fetching subjects</option>';
                                                }
                                            ?>
                                            <option selected disabled>Select Students:</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="enrollSecBtn">
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
<style>
    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
    }

    #studentTable td {
        height: 30px; 
        padding: 8px; 
    }
    #studentTable th {
        height: 30px;
        padding: 8px;
    }
</style>

<script>
document.getElementById('selstud').addEventListener('change', function() {
    var selectedOptions = Array.from(this.selectedOptions);
    var tableBody = document.querySelector('#studentTable tbody');

    // Clear existing rows before adding new ones
    tableBody.innerHTML = '';

    // Loop through the selected students and add rows to the table
    selectedOptions.forEach(function(option, index) {
        var studentID = option.value;
        var studentName = option.textContent;
        var studentLRN = option.getAttribute('data-subtext').split(': ')[1]; // Get the LRN from data-subtext
        var row = document.createElement('tr');

        // Add # column
        var cell1 = document.createElement('td');
        cell1.classList.add('text-center');
        cell1.textContent = index + 1 + '.';
        row.appendChild(cell1);

        // Add LRN column
        var cell2 = document.createElement('td');
        cell2.classList.add('text-center');
        cell2.textContent = studentLRN;
        row.appendChild(cell2);

        // Add Student Name column
        var cell3 = document.createElement('td');
        cell3.textContent = studentName;
        row.appendChild(cell3);

        // Append the row to the table
        tableBody.appendChild(row);
    });
});

</script>

<script>
$(document).ready(function(){
    $('.enroll-stud-btn').click(function(){
    
        var programID = $(this).data('program-id');
        var gradelvlID = $(this).data('gradelvl-id');
        var secID = $(this).data('sec-id');
        var secName = $(this).data('sec-name');
        var facultyID = $(this).data('faculty-id');
        var deptID = $(this).data('dept-id');
        
        $('#programID').val(programID);
        $('#gradelvlID').val(gradelvlID);
        $('#secIDenroll').val(secID);
        $('#secNameenroll').val(secName);
        $('#facultyIDenroll').val(facultyID);
        $('#deptIDenroll').val(deptID);
        
    });
});

</script>   


<script>
    document.addEventListener("DOMContentLoaded", function () {
    const selstud = document.getElementById('selstud');
    const selectedSub = document.getElementById('selectedStud');

    function updateSelectedSubjects() {
        // Clear existing chips
        selectedSub.innerHTML = 'Selected Students: ';

        // Create chips for each selected option
        Array.from(selstud.selectedOptions).forEach(option => {
            const chip = document.createElement('span');
            chip.className = 'badge bg-primary me-2';
            chip.innerText = option.text;
            chip.setAttribute('data-value', option.value);

            const closeButton = document.createElement('button');
            closeButton.className = 'btn-close btn-close-white ms-1';
            closeButton.setAttribute('aria-label', 'Close');
            closeButton.addEventListener('click', function (e) {
                e.preventDefault();
                option.selected = false;
                updateSelectedSubjects();
            });

            chip.appendChild(closeButton);
            selectedSub.appendChild(chip);
        });
    }

    // Initial update
    updateSelectedSubjects();

    // Update on change
    selstud.addEventListener('change', updateSelectedSubjects);
});

</script>





<?php 
require_once("includes/config.php");
$query = "SELECT ay.*, s.semName FROM academic_year ay
JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
$stmt = $conn->prepare($query);
$stmt->execute();
$acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
foreach ($acadYear as $row): 
endforeach 
?>
<!-- Modal Form enroll subjects-->
<div class="modal fade" id="enrollSubjects" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="modals/enroll_studentSubject.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Enroll Subjects</legend>
                        <input type="hidden" name="studID" value="<?php echo $studID; ?>">
                        <input type="hidden" name="semID" value="<?php echo $semID ?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID; ?>">
                        <input type="hidden" name="programID" value="<?php echo $programID ?>">
                        <input type="hidden" name="secID" value="<?php echo $secID; ?>">
                        <input type="hidden" name="ayID" value="<?php echo $ayID; ?>">
                        <input type="hidden" name="secName" value="<?php echo $secName; ?>">
                        <input type="hidden" name="facultyID" value="<?php echo $facultyID; ?>">
                        <input type="hidden" name="deptID" value="<?php echo $deptID; ?>">
                        <input type="hidden" name="studName" value="<?php echo $studName; ?>">
                        <input type="hidden" name="ayName" value="<?php echo $secAyName; ?>">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="" readonly value="<?php echo $row['ayName']; ?>">
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selsem" name="" readonly value="<?php echo $row['semName']; ?>">
                                    <input type="hidden" name="selSem" value="<?php echo $row['semID']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selSub" class="form-label fw-bold">Select Subjects:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" multiple data-live-search="true" id="selSub" name="selSub[]" required>
                                        <?php
                                        include("../includes/config.php");
                                        try {

                                            if($deptID == 3){
                                                $sql = "SELECT c.subjectID, s.subjectname 
                                                FROM curriculum c
                                                JOIN subjects s ON s.subjectID = c.subjectID 
                                                LEFT JOIN section_students ss ON c.subjectID = ss.subjectID AND ss.studID = :studID AND ss.secID = :secID
                                                WHERE c.isActive = 1 
                                                    AND c.semID = :semID 
                                                    AND c.gradelvlID = :gradelvlID 
                                                    AND c.programID = :programID
                                                    AND ss.subjectID IS NULL
                                                   ";

                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindParam(':semID', $semID, PDO::PARAM_INT);
                                                $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                                $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
                                                $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                                                $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                                $stmt->execute();
                                            }else{
                                                $sql = "SELECT c.subjectID, s.subjectname
                                                FROM curriculum c
                                                JOIN subjects s ON c.subjectID = s.subjectID
                                                JOIN subject_grade_levels sgl ON c.curriculumID = sgl.curriculumID
                                                LEFT JOIN section_students ss ON c.subjectID = ss.subjectID AND ss.studID = :studID AND ss.secID = :secID
                                                WHERE c.isActive = 1 
                                                AND sgl.gradelvlID = :gradelvlID
                                                AND ss.subjectID IS NULL";

                                                $stmt = $conn->prepare($sql);       
                                                $stmt->bindParam(':gradelvlID', $gradelvlID, PDO::PARAM_INT);
                                                $stmt->bindParam(':studID', $studID, PDO::PARAM_INT);
                                                $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                                $stmt->execute();
                                            }
                                            

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['subjectID'] . '">' . $row['subjectname'] . '</option>';
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
                            <div id="selectedSub" class="fw-bold">
                                Selected Subjects:
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="enrollSubBtn">
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const selSub = document.getElementById('selSub');
    const selectedSub = document.getElementById('selectedSub');

    function updateSelectedSubjects() {
        // Clear existing chips
        selectedSub.innerHTML = 'Selected Subjects: ';

        // Create chips for each selected option
        Array.from(selSub.selectedOptions).forEach(option => {
            const chip = document.createElement('span');
            chip.className = 'badge bg-primary me-2';
            chip.innerText = option.text;
            chip.setAttribute('data-value', option.value);

            const closeButton = document.createElement('button');
            closeButton.className = 'btn-close btn-close-white ms-1';
            closeButton.setAttribute('aria-label', 'Close');
            closeButton.addEventListener('click', function (e) {
                e.preventDefault();
                option.selected = false;
                updateSelectedSubjects();
            });

            chip.appendChild(closeButton);
            selectedSub.appendChild(chip);
        });
    }

    // Initial update
    updateSelectedSubjects();

    // Update on change
    selSub.addEventListener('change', updateSelectedSubjects);
});

</script>

<?php 
    require_once("includes/config.php");
    $query = "SELECT ay.*, s.semName FROM academic_year ay
    JOIN semester s ON ay.semID = s.semID WHERE ay.isActive=1";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $acadYear = $stmt->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($acadYear as $row): 
        endforeach 
?>
<!-- Modal Form for sections students -->
<div class="modal fade" id="addStudentsection" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <form action="modals/enroll_section.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
              <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Enroll Students</legend>
                        <input type="hidden" name="programID" value="<?php echo $programID?>">
                        <input type="hidden" name="gradelvlID" value="<?php echo $gradelvlID?>">
                        <input type="hidden" name="secID" value="<?php echo $secID?>">
                        <input type="hidden" name="secName" value="<?php echo $secName?>">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="" readonly value="<?php echo $row['ayName']; ?>">
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="selsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                </div>
                                    <input type="text" class="form-control" id="selsem" name="" readonly value="<?php echo $row['semName']; ?>">
                                    <input type="hidden" name="selSem" value="<?php echo $row['semID']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selstud2" class="form-label fw-bold">Select Student:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" multiple data-live-search="true" id="selstud2" name="selStud[]" required>
                                       <?php
                                        include("../includes/config.php");
                                            try {
                                                $sql = "SELECT * FROM students WHERE isActive = 1";
                                                $stmt = $conn->prepare($sql);
                                               $stmt->execute();
                                                // Output the select options
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row['studID'] . '">' . $row['lname'] .', '.$row['fname'].' '.$row['mname']. '</option>';
                                                }
                                            } catch (PDOException $e) {
                                                // Handle any errors that occur during query execution
                                                echo '<option disabled>Error fetching subjects</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div id="selectedStud2" class="fw-bold">
                                Selected Students:
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="enrollSectionBtn">
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


<!-- grade entry for subjects in section -->
<div class="modal fade" id="inputGradeModalSec" tabindex="-1" role="dialog" aria-labelledby="inputGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <?php 
                    $userTypeID = $_SESSION['userTypeID'];

                    $actionTo = match($userTypeID) {
                        1 => 'save_grade.php',  // This is admin
                        2 => '../gmsn/save_grade.php', // This is faculty
                        default => 'error_page.php',
                    };
                ?>
                <form action="<?php echo $actionTo; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                    <input type="hidden" id="enrollIDSec" name="enrollID">
                    <input type="hidden" id="subjectIDSec" name="subjectID">
                    <input type="hidden" id="studIDSec" name="studID">
                    <input type="hidden" id="semIDSec" name="semID">
                    <input type="hidden" id="gradelvlIDSec" name="gradelvlID">
                    <input type="hidden" id="programIDSec" name="programID">
                    <input type="hidden" id="ayIDSecSec" name="ayID">
                    <input type="hidden" id="subjectNameSec" name="subjectName">
                    <input type="hidden" id="studnameSec" name="studname">
                    <input type="hidden" id="deptIDSec" name="deptID">
                    <input type="hidden" id="facultyIDSec" name="facultyID">
                    <input type="hidden" id="secNameSec" name="secNameSec" value="<?php echo str_replace('+', ' ', urldecode($secName)); ?>">
                    <input type="hidden" id="secIDsec" name="secID" value="<?php echo $secID?>">
                    <input type="hidden" id="ayName" name="ayName" value="<?php echo $secAyName?>">
                    
                    <input type="hidden" id="activeSemID" name="activeSemID" value="<?php echo $activeSem?>">

                    <input type="hidden" name="userTypeID" value="<?php echo $userTypeID?>">
                    <legend class="mb-4" id="subjectNameLegendSec"></legend>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold" style="color: #1a237e">Name: <span id="studnameSecSpan"></span></h6>
                                <h6 class="fw-bold" style="color: #1a237e">LRN: <span id="studLRN"></span></h>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="txtgradeSub" class="fw-bold"><?php echo ($activeSem == 1) ? '1st Quarter Grade: ' : '3rd Quarter: ' ?></label>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    </div>
                                    <?php 
                                        $query = "SELECT * FROM gradepermission";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $permit = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                                        foreach ($permit as $gradePermit): 
                                            endforeach 
                                    ?>
                                    <input type="number" id="txtgradeSub" name="txtGrade" placeholder="Enter Grade" class="form-control" step="0.01" max="100" <?php //echo($gradePermit['_first'] == 0)? 'readonly' : ''?> oninput="validateInput(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="txtgradeSub2" class="fw-bold"><?php echo ($activeSem == 1) ? '2nd Quarter Grade: ' : '4th Quarter Grade: ' ?></label>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    </div>
                                    <input type="number" id="txtgradeSub2" name="txtGrade2" placeholder="Enter Grade" class="form-control" step="0.01" max="100" <?php //echo($gradePermit['_second'] == 0)? 'readonly' : ''?> oninput="validateInput(this)">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveGradeSubBtn">
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
<script>
    function validateInput(input) {
        let value =parseFloat(input.value);

        if (value < 60) {
            input.setCustomValidity("Grade must be atleast 60.");
        }else if (value > 100){
            input.setCustomValidity("Grade must not exceed 100");
        }else{
            input.setCustomValidity("");
        }
        input.reportValidity();
    }
</script>
                                        
<script>
$(document).on('click', '.grade-btn, .gradefac-btn', function() {
    var enrollID = $(this).data('enroll-id');
    var studID = $(this).data('stud-id');
    var subjectID = $(this).data('subject-id');
    var subjectName = $(this).data('subject-name');
    var first = $(this).data('grade-first');
    var second = $(this).data('grade-second');

    var gradelvlID = $(this).data('gradelvl-id');
    var semID = $(this).data('sem-id');
    var programID = $(this).data('program-id');
    var ayID = $(this).data('ay-id');
    var studName = $(this).data('stud-name');
    var deptID = $(this).data('dept-id');
    var facultyID = $(this).data('faculty-id');
    var lrn = $(this).data('stud-lrn');

    $('#enrollIDSec').val(enrollID);
    $('#studIDSec').val(studID);
    $('#subjectIDSec').val(subjectID);
    $('#subjectNameSec').val(subjectName);
    $('#subjectNameLegendSec').text(subjectName);

    $('#txtgradeSub').val(first !== null ? first : '');
    $('#txtgradeSub2').val(second !== null ? second : '');

    $('#semIDSec').val(semID);
    $('#gradelvlIDSec').val(gradelvlID);
    $('#programIDSec').val(programID);
    $('#ayIDSecSec').val(ayID);
    $('#studnameSec').val(studName);

    $('#studnameSecSpan').text(studName);
    $('#studLRN').text(lrn);
    $('#facultyIDSec').val(facultyID);
    $('#deptIDSec').val(deptID);
});

</script>   



<!-- grade entry for subjects in faculty -->
<div class="modal fade" id="inputGradeModalSub" tabindex="-1" role="dialog" aria-labelledby="inputGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <?php 
                $userTypeID = $_SESSION['userTypeID'];
                if($userTypeID == 1){
                    $actionTo = 'save_grade.php';
                }else{
                    $actionTo = '../gmsn/save_grade.php';
                }
            ?>
            <form action="<?php echo $actionTo; ?>" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                    <input type="hidden" id="enrollIDSub" name="enrollID">
                    <input type="hidden" id="subjectIDSub" name="subjectID">
                    <input type="hidden" id="studIDSub" name="studID">
                    <input type="hidden" id="semIDSub" name="semID">
                    <input type="hidden" id="gradelvlIDSub" name="gradelvlID">
                    <input type="hidden" id="programIDSub" name="programID">
                    <input type="hidden" id="subjectNameSecSub" name="subjectName">
                    <input type="hidden" id="secIDSub" name="secID">
                    <input type="hidden" id="secNameSub" name="secName">
                    <input type="hidden" id="facultyIDSub" name="facultyID">
                    <input type="hidden" id="facultyNameSub" name="facultyName">
                    <input type="hidden" id="ayIDSub" name="ayID">
                    <input type="hidden" id="userTypeID" name="userTypeID" value="<?php echo $userTypeID?>">
                    <input type="hidden" id="deptID" name="deptID" value="<?php echo $deptID;?>">

                    <input type="hidden" id="faID" name="faID" value="<?php echo $faID?>">

                    <input type="hidden" id="activeSemID" name="activeSemID" value="<?php echo $activeSem?>">
                    <input type="hidden" id="ayName" name="ayName" value="<?php echo $ayName?>">

                    
                    <legend class="mb-4" id="subjectNameLegend"><?php echo $subjectName ?></legend>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold" style="color: #1a237e"><span style="color: black" class="fw-normal me-2">Name</span>: <?php echo $studName; ?></h6>
                                <h6 class="fw-bold" style="color: #1a237e"><span style="color: black" class="fw-normal me-3">LRN</span> :<?php echo $lrn; ?></h>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                <label for="txtgradeSubSub" class="fw-bold"><?php echo ($activeSem == 1) ? '1st Quarter Grade: ' : '3rd Quarter Grade: ';?></label>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    </div>
                                    <input type="number" id="txtgradeSubSub" name="txtGrade" placeholder="Enter Grade" class="form-control" step="0.01" max="100" <?php //echo ($gradePermit['_first'] == 0) ? 'readonly' : ''?> oninput="validateInput(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">
                                    <label for="txtgradeSub2" class="fw-bold"><?php echo ($activeSem == 1) ? '2nd Quarter Grade: ' : '4th Quarter Grade: ';?></label>
                            </div>
                            <div class="col-md-7 mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    </div>
                                <input type="number" id="txtgradeSubSub2" name="txtGrade2" placeholder="Enter Grade" class="form-control" step="0.01" min="60" max="100" <?php //echo ($gradePermit['_second'] == 0) ? 'readonly' : ''?> oninput="validateInput(this)">                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveGradeSubSubBtn">
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
<script>
function validateInput(input) {
    let value = parseFloat(input.value);

    if (value < 60) {
        input.setCustomValidity("Grade must be at least 60.");
    } else if (value > 100) {
        input.setCustomValidity("Grade must not exceed 100.");
    } else {
        input.setCustomValidity("");
    }
    
    input.reportValidity();
}
</script>
<script>
$(document).ready(function(){
    $('.grade-btn').click(function(){
        var enrollID = $(this).data('enroll-id');
        var studID = $(this).data('stud-id');
        var subjectID = $(this).data('subject-id');
        var subjectName = $(this).data('subject-name');
        var first = $(this).data('grade-first');
        var second = $(this).data('grade-second');
        var secID = $(this).data('sec-id');
        var secName = $(this).data('sec-name');
        var facultyID = $(this).data('fac-id');
        var facultyName = $(this).data('fac-name');
        var ayID = $(this).data('session-id');
        var semID = $(this).data('sem-id');
        var gradelvlID = $(this).data('gradelvl-id');
        var programID = $(this).data('program-id');

        $('#enrollIDSub').val(enrollID);
        $('#studIDSub').val(studID);
        $('#subjectIDSub').val(subjectID);
        $('#subjectNameSecSub').val(subjectName);
        $('#semIDSub').val(semID);
        $('#gradelvlIDSub').val(gradelvlID);
        $('#programIDSub').val(programID);
        $('#subjectNameLegendSub').text(subjectName);
        if (first !== 0) {
            $('#txtgradeSubSub').val(first);
        }

        if (second !== 0) {
            $('#txtgradeSubSub2').val(second);
        }
        $('#secIDSub').val(secID);
        $('#secNameSub').val(secName);
        $('#facultyIDSub').val(facultyID);
        $('#facultyNameSub').val(facultyName);
        $('#ayIDSub').val(ayID);
    });
});

</script>   



<!-- Modal for Promoting Students -->
<div class="modal fade" id="promoteStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true" data-stud-ids="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="modals/enroll_student.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Promote Students</legend>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selstud" class="form-label fw-bold">Select Section:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" data-live-search="true" id="selsec" name="selSec" required>
                                        <?php
                                        include("../includes/config.php");
                                        $deptID = $_GET['deptID'] ?? ''; 
                                        $programID = $_GET['programID'] ?? '';
                                        $semID = $_GET['semID'] ?? '';
                                        try {
                                            $sql = "SELECT s.*, 
                                                    (SELECT programcode FROM programs WHERE programID = s.programID) AS programname,
                                                    (SELECT gradelvl FROM grade_level WHERE gradelvlID = s.gradelvlID) AS gradelvl,
                                                    (SELECT ayName FROM academic_year WHERE ayID = s.ayID) AS ay,
                                                    (SELECT semName FROM semester WHERE semID = s.semID) AS sem 
                                                FROM sections s
                                                WHERE deptID = :deptID
                                                AND programID = :programID
                                                AND semID != :semID
                                                ORDER BY ay DESC";

                                            $stmt = $conn->prepare($sql);
                                            $stmt->bindParam(':deptID', $deptID, PDO::PARAM_INT); 
                                            $stmt->bindParam(':programID', $programID, PDO::PARAM_INT); 
                                            $stmt->bindParam(':semID', $semID, PDO::PARAM_INT); 
                                            $stmt->execute();

                                            // Output the select options
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['secID'] . '" data-studid="' . $row['studID'] . '">' . $row['programname'] . ' ' . $row['gradelvl'] . ' - ' . $row['secName'] . ' (' . $row['ay'] . ' - ' . $row['sem'] . ') ' . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            // Handle any errors that occur during query execution
                                            echo '<option disabled>Error fetching sections</option>';
                                        }
                                        ?>
                                    </select>

                                    </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="promoteBtn">
                            <i class="bi bi-check-circle"></i> Promote
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




<!-- Modal Form section student irreular-->
<div class="modal fade" id="addIrregular" tabindex="-1" role="dialog" aria-labelledby="addStudentLabel" aria-hidden="true">
    <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content">
            <div class="modal-body">
            <form action="modals/enroll_studentSec.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <fieldset class="border p-4 rounded mb-4">
                    <legend class="mb-4">Enroll Students</legend>
                    <input type="hidden" id="programID" name="programID" value="<?php echo $programID?>">
                    <input type="hidden" id="gradelvlID" name="gradelvlID" value="<?php echo $gradelvlID?>">
                    <input type="hidden" id="secIDenroll" name="secID" value="<?php echo $secID?>">
                    <input type="hidden" id="secNameenroll" name="secName" value="<?php echo $sectionName?>">
                    <input type="hidden" id="facultyIDenroll" name="facultyID" value="<?php echo $facultyID?>">
                    <input type="hidden" id="deptIDenroll" name="deptID" value="<?php echo $deptID?>">
                    <input type="hidden" id="ayName" name="ayName" value="<?php echo $secAyName?>">
                    <input type="hidden" id="isIrreg" name="isIrreg" value="1">


                    <div class="row">
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="studentTables">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">LRN</th>
                                            <th>Student Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Dynamic content here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="hidden" name="selAY" value="<?php echo $secAyName; ?>">
                        <input type="hidden" name="selSem" value="<?php echo $secSem; ?>">


                        <div class="col-md-5">
                            <!-- Add your form fields for the left column here -->
                            <div class="col-md-12">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" multiple data-live-search="true" id="selStudUpdated" name="selStud[]" required>
                                    <?php
                                        include("../includes/config.php");
                                        try {
                                            $sql = "SELECT * FROM students 
                                                    WHERE isActive = 1 
                                                    AND studID NOT IN (SELECT studID FROM section_students WHERE secID = :secID AND subjectID IS NULL)
                                                    ORDER BY lname ASC";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bindParam(':secID', $secID, PDO::PARAM_INT);
                                            $stmt->execute();

                                            // Output the select options
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $studentLRN = $row['lrn'];
                                                echo '<option value="' . $row['studID'] . '" data-subtext="LRN : '.$studentLRN .'">' . ucwords(strtolower($row['lname'])) .', '.ucwords(strtolower($row['fname'])).' '.ucwords(strtolower($row['mname'])). '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            // Handle any errors that occur during query execution
                                            echo '<option disabled>Error fetching subjects</option>';
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                </fieldset>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary me-md-2" name="enrollSecBtn">
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const selStud = document.getElementById('selStudUpdated');  // Updated ID
    const selectedStudTable = document.getElementById('studentTables').getElementsByTagName('tbody')[0];  // Accessing the table body directly

    function updateSelectedStudents() {
        // Clear existing table rows
        selectedStudTable.innerHTML = '';

        // Create a row for each selected student
        Array.from(selStud.selectedOptions).forEach(option => {
            const row = document.createElement('tr');
            
            const cell1 = document.createElement('td');
            cell1.classList.add('text-center');
            cell1.innerText = selectedStudTable.rows.length + 1 +'.'; // Automatically number the rows

            const cell2 = document.createElement('td');
            cell2.classList.add('text-center');
            cell2.innerText = option.getAttribute('data-subtext'); // Show LRN

            const cell3 = document.createElement('td');
            cell3.innerText = option.text;  // Student Name

            // Add the cells to the row
            row.appendChild(cell1);
            row.appendChild(cell2);
            row.appendChild(cell3);

            // Add the row to the table
            selectedStudTable.appendChild(row);
        });
    }

    // Initial update
    updateSelectedStudents();

    // Update the table when selection changes
    selStud.addEventListener('change', updateSelectedStudents);
});


</script>