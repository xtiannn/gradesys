

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
<!-- Modal Form -->
<div class="modal fade" id="addSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_section.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Create Section</legend>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="selay" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="selay" name="selAY" readonly value="<?php echo $row['ayName']; ?>" required>
                                    <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
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
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="selpro" class="form-label fw-bold">Program:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-book"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="selpro" name="selProg" required>
                                        <option selected disabled value>Select Program</option>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                            $query = "SELECT * FROM programs WHERE isActive=1";
                                            $stmt = $conn->query($query);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['programID'] . '">' . $row['programname'] . '</option>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<option disabled>Error fetching Programs</option>';
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a program.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gradelvl" class="form-label fw-bold">Grade Level:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="gradelvl" name="selgradelvl" required>
                                        <option selected disabled value>Select Grade Level</option>
                                        <?php
                                        require_once("./includes/config.php");
                                        try {
                                            $query = "SELECT * FROM grade_level WHERE isActive=1";
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


<!-- UPDATE Form -->
<div class="modal fade" id="updateSectionModal" tabindex="-1" role="dialog" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_section.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <input type="hidden" name="sectionID" id="sectionID">
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
                                <input type="text" class="form-control" id="updateAY" name="" readonly value="<?php echo $row['ayName']; ?>">
                                <input type="hidden" name="selAY" value="<?php echo $row['ayID']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                                <label for="updatesem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
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
                                    <input type="text" class="form-control" id="updatesem" name="" readonly value="<?php echo $row['semName']; ?>">
                                    <input type="hidden" name="selSem" value="<?php echo $row['semID']; ?>">
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
                                <select class="form-select form-control selectpicker" id="updateprogram" name="selProg">
                                <option selected disabled>Select Program</option>
                                <?php
                                require_once("./includes/config.php");
                                try {
                                    $query = "SELECT * FROM programs WHERE isActive=1";

                                    $stmt = $conn->query($query);

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row['programID'] . '">' . $row['programname'] . '</option>';
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
                                    <select class="form-select form-control selectpicker" id="updategradelvl" name="selgradelvl">
                                <option selected disabled>Grade Level</option>
                            <?php
                            require_once("./includes/config.php");
                            try {
                                // Prepare a SQL query to retrieve subjects
                                $query = "SELECT * FROM grade_level WHERE isActive=1";

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
                            <div class="col-md-12 mb-3">
                                <label for="updatesection" class="form-label fw-bold">Section:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="updatesection" name="txtsection" placeholder="Enter Section">
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
    $('.update-btn').click(function(){
        var sectionID = $(this).data('section-id');
        var secName = $(this).data('section-name');
        var ay = $(this).data('section-ay');
        var program = $(this).data('section-program');
        var sem = $(this).data('section-sem');
        var gradelvl = $(this).data('section-gradelvl');

        $('#sectionID').val(sectionID);
        $('#updatesection').val(secName);
        
        $('#updateAY option, #updateprogram option, #updatesem option, #updategradelvl option').each(function() {
            if ($(this).val() == ay || $(this).val() == program || $(this).val() == sem || $(this).val() == gradelvl) {
                $(this).attr('selected', 'selected', 'selected', 'selected');
            }
        });

        $('#updateAY, #updateprogram, #updatesem ,#updategradelvl').trigger('change');
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
                            title: 'Oops...',
                            text: 'Failed to delete the section: ' + data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
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
