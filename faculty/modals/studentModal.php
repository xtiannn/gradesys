<!-- Enroll Student Modal -->
<div class="modal fade modal-xl" id="viewStudentModal" tabindex="-1" aria-labelledby="viewStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewStudentModalLabel">Enroll Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- First modal-body -->
      <div class="modal-body">
        <div id="studentDetails">
            <!-- Details will be populated via AJAX -->
        </div>
      </div>

      <!-- Second modal-body -->
      <div class="modal-body">
        <div id="enrollDetails">
            <div class="col-lg-12">
                <div class="card">
                    <form action="#" method="post">
                        <div class="row">
                        <div class="col-md-3">
                            <select class="form-select" id="selgradelvl" name="selGradelvl">
                                <option selected disabled>Grade Level</option>
                                <?php
                                try {
                                    // Prepare a SQL query to retrieve subjects
                                    $query = "SELECT * FROM grade_level WHERE isActive=1 ORDER BY CAST(SUBSTRING(gradelvl, 7) AS UNSIGNED);";

                                    // Execute the query
                                    $stmt = $conn->query($query);

                                    // Fetch subjects and populate the select dropdown
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row['gradelvlID'] . '">' . $row['gradelvl'] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    // Handle database connection errors
                                    echo '<option disabled>Error fetching gradelvl</option>';
                                }
                                ?>
                            </select>
                            </div>
                            <div class="col-md-3">
                            <select class="form-select" id="selsection" name="selSection">
                                <option selected disabled>Section</option>
                                <?php
                                try {
                                    // Prepare a SQL query to retrieve subjects
                                    $query = "SELECT * FROM sections WHERE isActive=1";

                                    // Execute the query
                                    $stmt = $conn->query($query);

                                    // Fetch subjects and populate the select dropdown
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row['secID'] . '">' . $row['secName'] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    // Handle database connection errors
                                    echo '<option disabled>Error fetching section</option>';
                                }
                                ?>
                            </select>
                            </div>
                            <div class="col-md-3">
                            <select class="form-select" id="selsem" name="selSem" disabled>
                                <option selected disabled>Semester</option>
                                <?php
                                try {
                                    // Prepare a SQL query to retrieve subjects
                                    $query = "SELECT * FROM semester";

                                    // Execute the query
                                    $stmt = $conn->query($query);

                                    // Fetch subjects and populate the select dropdown
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row['semID'] . '">' . $row['semName'] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    // Handle database connection errors
                                    echo '<option disabled>Error fetching semester</option>';
                                }
                                ?>
                            </select>
                            </div>
                            <div class="col-md-3">
                            <select class="form-select" id="selprogram" name="selProgram" disabled>
                                <option selected disabled>Program</option>
                                <?php
                                require_once("./includes/config.php");
                                try {
                                    // Prepare a SQL query to retrieve subjects
                                    $query = "SELECT * FROM programs WHERE isActive=1";

                                    // Execute the query
                                    $stmt = $conn->query($query);

                                    // Fetch subjects and populate the select dropdown
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $row['programID'] . '">' . $row['programcode'].' - '. $row['programname'] . '</option>';
                                    }
                                } catch (PDOException $e) {
                                    // Handle database connection errors
                                    echo '<option disabled>Error fetching programs</option>';
                                }
                                ?>
                            </select>
                            </div>
                        </div>
                    </form>
                    <div class="card-body" id="subjecttable">
                    <!-- Table with stripped rows -->
                    <table class="table table-striped">
                        <thead>
                            <?php 
                                require_once("includes/config.php");
                                $query = "SELECT s.*, sm.semCode, gl.gradelvlcode, p.programcode
                                FROM subjects s 
                                JOIN semester sm ON s.semID = sm.semID
                                JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
                                JOIN programs p ON s.progID = p.programID
                                WHERE s.isActive = 1";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);  
                                $count = 0;
                            ?>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($subjects as $row): ?>
                                <tr>
                                    <td><?php echo ++$count; ?></td> <!-- Increment count for each program -->
                                    <td><?php echo htmlspecialchars($row['subjectcode']); ?></td>
                                    <td><?php echo htmlspecialchars($row['subjectname']); ?></td>
                                    <td>
                                        <div class="">
                                        <input type="checkbox" class="form-check-input form-check-lg" id="checkbox_<?php echo $row['subjectID']; ?>" data-row-id="<?php echo $row['subjectID']; ?>">                                        </label>
                                        </div>
                                    </td>


                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Enroll</button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="assets/jquery-3.7.1.min.js"></script>


<script>
    // Function to handle delete button click
    function handleDeleteButtonClick(studentId, studentName) {
    Swal.fire({
    title: 'Are you sure?',
    text: 'You are about to delete the student: ' + studentName,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
    if (result.isConfirmed) {
        // Send AJAX request to delete_student.php
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_student.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Display success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The student has been deleted successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Reload the page or update the table if needed
                            location.reload(); // Reload the page
                            // You can update the table without reloading the page
                            // Example: this.closest('tr').remove();
                        });
                    } else {
                        // Display error message if deletion fails
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Failed to delete the student: ' + response.message
                        });
                    }
                } else {
                    // Display error message if request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to delete the student. Please try again later.'
                    });
                }
            }
        };
        xhr.send('studentId=' + studentId); // Send the student ID to the server
        }
    });
}


// Attach event listener to delete buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default form submission behavior
            var studentId = this.getAttribute('data-student-id');
            var studentName = this.closest('tr').querySelector('td:nth-child(3)').innerText; // Get the student name from the row
            handleDeleteButtonClick(studentId, studentName);
        });
    });
});
</script>
