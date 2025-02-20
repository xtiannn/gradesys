
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
    
</style>

<!-- Modal Form -->
<div class="modal fade" id="addCurr" tabindex="-1" role="dialog" aria-labelledby="addCurrLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="save_curriculum.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Add Curriculum</legend>
                        <input type="hidden" name="program_id" value="<?php echo htmlspecialchars($_GET['program_id']); ?>">
                        <input type="hidden" name="deptID" value="<?php echo htmlspecialchars($_GET['deptID']); ?>">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="selsub" class="form-label fw-bold">Select Subject:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="selsub" name="selSub[]" data-live-search="true" required>
                                        <option selected disabled value="">Select Subject:</option>
                                        <?php
                                        include("../includes/config.php");

                                        if(isset($_GET['program_id'])) {
                                            $programID = $_GET['program_id'];

                                            try {
                                                $sql = "SELECT s.subjectID, s.subjectcode, s.subjectname
                                                FROM subjects s
                                                JOIN subject_program sp ON s.subjectID = sp.subjectID
                                                WHERE sp.programID = :programID
                                                AND s.subjectID IN (
                                                    SELECT subjectID
                                                    FROM curriculum
                                                    WHERE programID = $programID AND curriculumID NOT IN (SELECT curriculumID FROM subject_grade_levels)
                                                )
                                                AND s.isActive = 1";

                                                $stmt = $conn->prepare($sql);
                                                $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
                                                $stmt->execute();

                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row['subjectID'] . '">' . $row['subjectcode'] .' - ' .  $row['subjectname'] . '</option>';
                                                }
                                            } catch (PDOException $e) {
                                                echo '<option disabled>Error fetching subjects</option>';
                                            }
                                        } else {
                                            echo '<option disabled>Program ID not provided</option>';
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="level" class="form-label fw-bold">Grade Level:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-journal-check"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="level" multiple name="selLevel[]" required>
                                    <?php
                                    try {
                                        $sql = "SELECT * FROM grade_level WHERE isActive=1 AND gradelvl NOT IN ('Grade 11', 'Grade 12')";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute();

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row['gradelvlID'] . '">' . $row['gradelvl'] . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<option disabled>Error fetching Grade Level</option>';
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveCurriculumBtn">
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

<!-- update form -->
<div class="modal fade" id="updateCurr" tabindex="-1" role="dialog" aria-labelledby="addCurrLabel" aria-hidden="true">            <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-body">
            <form action="update_curriculumJHS.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                <fieldset class="border p-4 rounded mb-4">
                    <legend class="mb-4">Update Curriculum</legend>
                    <input type="hidden" name="curriculumID" id="upcurriculumID">
                    <input type="hidden" name="programID" id="programID" value="<?php echo $programID?>">
                    <input type="hidden" name="subjectID" id="upsubjectID">
                    <input type="hidden" name="deptID" id="deptID" value="<?php echo $deptID; ?>">
                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="upselsub" class="form-label fw-bold">Subject:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-journal"></i></span>
                                </div>
                                <input type="hidden" id="upselsubHidden" name="selSub">
                                <input type="text" class="form-control" id="upselsub" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label for="uplevel" class="form-label fw-bold">Grade Level:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-journal-check"></i></span>
                                </div>
                                <select class="form-select form-control selectpicker" id="uplevel" multiple name="selLevel[]" required>
                                <option selected disabled value="">Select Level:</option>
                                <?php
                                try {
                                    $sql = "SELECT * FROM grade_level WHERE isActive=1 AND gradelvl NOT IN ('Grade 11', 'Grade 12')";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row['gradelvlID'] . '">' . $row['gradelvl'] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    echo '<option disabled>Error fetching Grade Level</option>';
                                }
                                ?>
                            </select>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary me-md-2" name="updateCurriculumBtn">
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




<script>
$(document).ready(function() { 
    $('.updateCurr-btn').click(function() {
        var curriculumID = $(this).data('curri-id');
        var subjectID = $(this).data('subject-id');
        var programID = $(this).data('subject-program');
        var subjectname = $(this).data('subject-name');
        var gradelvlIDs = $(this).data('gradelvl-ids');

        $('#upcurriculumID').val(curriculumID);
        $('#upsubjectID').val(subjectID);
        $('#upprogramID').val(programID);
        $('#upselsubHidden').val(subjectID);
        $('#upselsub').val(subjectname);


        // Select prerequisites
        var gradelvlArray = gradelvlIDs ? gradelvlIDs.split(',') : [];
        $('#uplevel').val(gradelvlArray).trigger('change');

        $('#updateCurr').modal('show');
    });
    
});
document.addEventListener('DOMContentLoaded', function () {
    const selSub = document.getElementById('selsub');
    const prere = document.getElementById('prere');

    selSub.addEventListener('change', function () {
        const selectedValue = selSub.value;
        const options = prere.querySelectorAll('option');

        options.forEach(option => {
            option.style.display = option.value === selectedValue ? 'none' : 'block';
        });

        // Refresh the selectpicker to reflect changes
        $('.selectpicker').selectpicker('refresh');
    });
});

</script>


