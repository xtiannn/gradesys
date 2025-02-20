<?php require_once "fetch/fetch_gradePermission.php"?>
<!-- modal for grade entry -->
<div class="modal fade" id="gradeInputSB" tabindex="-1" role="dialog" aria-labelledby="gradeInputSBLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="save_grade.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Input Grade</legend>
                        <input type="hidden" name="deptID" id="deptIDInput">
                        <input type="hidden" name="gradelvlID" id="gradelvlID">
                        <input type="hidden" name="secID" id="secID">
                        <div class="row mb-3">
                            <div class="col-md-5 mb-3">
                                <label for="sessionUpdateSB" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-outline-secondary" id="yearPickerButtonSB">
                                            <i class="bi bi-calendar2"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" id="sessionUpdateSB" name="txtAYName" placeholder="YYYY - YYYY" readonly>
                                    <input type="hidden" name="startYear" id="startYearSB">
                                    <input type="hidden" name="endYear" id="endYearSB">                                
                                </div>
                            </div>
                            <div class="col-md-7 mb-3">
                                <label for="upsemSB" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="upsemSB" name="selSem" required>
                                        <?php
                                            try {
                                                $sql = "SELECT * FROM semester";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();

                                                // Output the select options
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    echo '<option value="' . $row['semID'] . '">' . $row['semName'] . '</option>';
                                                }
                                            } catch (PDOException $e) {
                                                echo '<option disabled>Error fetching semester</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="studID" class="form-label fw-bold">Student:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <select class="form-select form-control selectpicker" data-live-search="true" id="studID" name="studID" required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="subjectID" class="form-label fw-bold">Subject:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-book"></i></span>
                                    <select class="form-select form-control selectpicker" data-live-search="true" id="subjectID" name="subjectID" disabled required>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="gradeInputsContainer" style="display: none;">
                            <div class="col-md-6 mb-3">
                                <label for="firstInput" class="form-label fw-bold">Q1 Grade:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    <input type="number" 
                                            id="firstInput" 
                                            name="firstInput" 
                                            class="form-control"
                                            placeholder="Input Grade"
                                            step="0.01" max="100"
                                            oninput="validateInput(this)"
                                            >
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="secondInput" class="form-label fw-bold">Q2 Grade:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-star"></i></span>
                                    <input type="number" 
                                            id="secondInput" 
                                            name="secondInput" 
                                            class="form-control" 
                                            placeholder="Input Grade"
                                            step="0.01" max="100"
                                            oninput="validateInput(this)"
                                            >
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="btnSaveGrade">
                            <i class="bi bi-save"></i> Save
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
    $(document).ready(function() {
        function fetchGrades() {
            var studID = $('#studID').val();  // Student ID
            var subjectID = $('#subjectID').val();  // Selected subject ID

            $.ajax({
                url: 'fetch_existingGrade.php',
                method: 'POST',
                data: {
                    studID: studID,
                    subjectID: subjectID
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data.error) {
                        console.error(data.error);  // Handle error if any
                    } else {
                        // Check semester and populate input fields accordingly
                        if (data.semID == 1) {
                            $('#firstInput').val(data.grade);  // Set first semester grade
                            $('#secondInput').val(data.grade2);  // Set second semester grade
                        } else if (data.semID == 2) {
                            $('#firstInput').val(data.grade3);  // Set third semester grade
                            $('#secondInput').val(data.grade4);  // Set fourth semester grade
                        }

                        // Make the input fields editable
                        $('#firstInput').prop('readonly', false);
                        $('#secondInput').prop('readonly', false);
                    }
                }
            });
        }

        function updateGradeLabels() {
            var semester = $('#upsemSB').val();
            
            if (semester === "1") {
                $('label[for="firstInput"]').text("Q1 Grade:");
                $('label[for="secondInput"]').text("Q2 Grade:");
            } else if (semester === "2") {
                $('label[for="firstInput"]').text("Q3 Grade:");
                $('label[for="secondInput"]').text("Q4 Grade:");
            }
        }
        // Trigger update when semester is changed
        $('#upsemSB').change(function() {
            updateGradeLabels();
            loadStudents(); // Reload students based on the selected academic year and semester
        });

        // Show/hide grade inputs based on subject selection
        $('#subjectID').change(function() {
            var selectedSubject = $(this).val();
            
            if (selectedSubject) {
                $('#gradeInputsContainer').show(); 
                fetchGrades();
            } else {
                $('#gradeInputsContainer').hide(); 
            }
        });

        // Initially hide grade inputs when the modal is opened
        $('#gradeInputSB').on('show.bs.modal', function() {
            $('#gradeInputsContainer').hide();
        });

        // Initial update for when the modal is first shown
        $('.input-gradeSB-btn').click(function() {
            updateGradeLabels();
            $('#gradeInputSB').modal('show');
        });
        
        function loadStudents() {
            var academicYear = $('#sessionUpdateSB').val();
            var semID = $('#upsemSB').val();
            
            
            if (academicYear && semID) {
                $.ajax({
                    url: 'fetch/fetch_students.php',
                    type: 'POST',
                    data: {
                        txtAYName: academicYear,
                        semID: semID
                    },
                    success: function(response) {
                        $('#studID').html(response);
                        $('#studID').selectpicker('refresh'); 
                    },
                    error: function() {
                        alert("Error fetching students.");
                    }
                });
            } else {
                $('#studID').html('<option selected disabled>Select/Search Student</option>');
                $('#studID').selectpicker('refresh');
            }
        }


        $('#sessionUpdateSB').on('change', loadStudents);
        $('#upsemSB').on('change', loadStudents);

        $('#studID').change(function() {
            var studID = $(this).val(); 
            var ayName = $('#sessionUpdateSB').val(); 
            var semID = $('#upsemSB').val();

            if (studID && ayName && semID) {
                $.ajax({
                    url: 'fetch/fetch_subjects.php', 
                    type: 'POST',
                    data: { 
                        studID: studID,
                        ayName: ayName, 
                        semID: semID 
                    },
                    success: function(response) {
                        console.log(response); 
                        $('#subjectID').html(response); 
                        $('#subjectID').prop('disabled', false);
                        $('#subjectID').selectpicker('refresh');
                    },
                    error: function() {
                        alert("Error fetching subjects.");
                    }
                });
            } else {
                $('#subjectID').html('<option selected disabled>Select Subject</option>');
                $('#subjectID').prop('disabled', true); 
                $('#subjectID').selectpicker('refresh');
            }

            $('#subjectID').change(function() {
                // Get the selected subject option
                var selectedOption = $(this).find('option:selected');

                // Get the values from the selected option's data attributes
                var gradelvlID = selectedOption.data('gradelvlid');
                var secID = selectedOption.data('secid');
                var deptID = selectedOption.data('deptid');

                var semID = $('#upsemSB').val(); // Get the selected semester ID

                var grade, grade2, grade3, grade4;


                if (semID == 1) {
                    // If semID is 1, use grade and grade2
                    grade = selectedOption.data('grade');
                    grade2 = selectedOption.data('grade2');
                    
                    $('#firstInput').val(grade); 
                    $('#secondInput').val(grade2); 
                } else {
                    // If semID is not 1, use grade3 and grade4
                    grade3 = selectedOption.data('grade3'); 
                    grade4 = selectedOption.data('grade4');

                    $('#firstInput').val(grade3);  
                    $('#secondInput').val(grade4);  
                }



                $('#gradelvlID').val(gradelvlID);
                $('#secID').val(secID);
                $('#deptIDInput').val(deptID); 


                // Show the grade input fields if needed
                $('#gradeInputsContainer').show();
            });

            fetchGrades();
        });



        // Year picker functionality
        $('#yearPickerButtonSB').datepicker({
            format: "yyyy",
            startView: "years",
            minViewMode: "years",
            autoclose: true,
        }).on('changeDate', function(e) {
            if (e.date) {
                var selectedYear = e.date.getFullYear();
                $('#sessionUpdateSB').val(selectedYear + ' - ' + (selectedYear + 1));
                $('#startYearSB').val(selectedYear);
                $('#endYearSB').val(selectedYear + 1);
                loadStudents(); // Reload students after year change
            }
        });

        // Show modal and set data without changing semester
        $('.input-gradeSB-btn').click(function() {
            var ayID = $(this).data('ay-id');
            var start = $(this).data('ay-start');
            var end = $(this).data('ay-end');
            var sem = $(this).data('ay-term');

            $('#ayID').val(ayID);
            $('#sessionUpdateSB').val(start + ' - ' + end);
            $('#startYearSB').val(start);
            $('#endYearSB').val(end);
            
            // Set the term based on sem value
            $('#upsemSB').val(sem).change();

            // Refresh the selects to show initial selections
            $('.selectpicker').selectpicker('refresh');
            $('#gradeInputSB').modal('show');
        });
    });
</script>


