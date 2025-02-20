<!-- CSS -->

<link rel="stylesheet" href="assets/bootstrap.min.css">
<link rel="stylesheet" href="assets/bootstrap-select.css">
<script src="assets/sweetalert2.all.min.js"></script>
<!-- JavaScript -->
<script src="assets/2.1.1-jquery.min.js"></script>
<script src="assets/bootstrap.bundle.min.js"></script>
<script src="assets/bootstrap-select.min.js"></script>

<script src="assets/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="assets/bootstrap-datepicker.min.css">
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


<!-- update Form -->
<div class="modal fade" id="updateAYModal" tabindex="-1" role="dialog" aria-labelledby="addAYModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form id="updateAYForm" action="save_ay.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <input type="hidden" name="ayID" id="ayID">
                        <legend class="mb-4">Update Session</legend>
                        <div class="row mb-3">
                            <div class="col-md-5 mb-3">
                                <label for="sessionUpdate" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-outline-secondary" id="yearPickerButton">
                                            <i class="bi bi-calendar2"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" id="sessionUpdate" name="txtAYName" placeholder="YYYY - YYYY" readonly>
                                </div>
                            </div>
                            <div class="col-md-7 mb-3">
                                <label for="upsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="upsem" name="selSem" required>
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
                                            // Handle any errors that occur during query execution
                                            echo '<option disabled>Error fetching semester</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateAYBtn">
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
$(document).ready(function() {
    // Initialize datepicker
    $('#yearPickerButton').datepicker({
        format: "yyyy",
        startView: "years",
        minViewMode: "years",
        autoclose: true,
    }).on('changeDate', function(e) {
        var selectedYear = e.date.getFullYear();
        $('#sessionUpdate').val(selectedYear + ' - ' + (selectedYear + 1));
    });

    // Show modal and set selected values
    $('.updateAY-btn').click(function(){
        var ayID = $(this).data('ay-id');
        var start = $(this).data('ay-start');
        var end = $(this).data('ay-end');
        var sem = $(this).data('ay-term');

        $('#ayID').val(ayID);
        $('#sessionUpdate').val(start + '-' + end);

        $('#upsem').val(sem).change();
        $('.selectpicker').selectpicker('refresh');

        $('#updateAYModal').modal('show');
    });
});
</script>

<!-- Modal Form -->
<!-- <div class="modal fade" id="addAYModal" tabindex="-1" role="dialog" aria-labelledby="addAYModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form id="saveAYForm" action="save_ay.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">New Academic Year</legend>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="session" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="session" name="txtAYName" placeholder="YYYY - YYYY" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="sem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="sem" name="selSem">
                                    <option selected disabled> Select Semester</option>
                                <?php
                                require_once("includes/config.php");
                                try {
                                    $query = "SELECT * FROM semester";

                                    $stmt = $conn->query($query);

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
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveAYBtn">
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
</div> -->

<!-- update Form -->
<!-- <div class="modal fade" id="updateAYModal" tabindex="-1" role="dialog" aria-labelledby="addAYModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form id="updateAYForm" action="save_ay.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <input type="hidden" name="ayID" id="ayID">
                        <legend class="mb-4">Update Session</legend>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="sessionUpdate" class="form-label fw-bold">Academic Year:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-calendar2"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="sessionUpdate" name="txtAYName" placeholder="YYYY - YYYY">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="upsem" class="form-label fw-bold">Term:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                    <select class="form-select form-control selectpicker" id="upsem" name="selSem" required>
                                    <option selected disabled>Semester</option>
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
                                        // Handle any errors that occur during query execution
                                        echo '<option disabled>Error fetching semester</option>';
                                    }
                                    ?>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="status" class="form-label fw-bold">Status:</label>
                                <div class="btn-group btn-toggle" id="status"> 
                                    <button type="button" class="btn btn-sm btn-default" id="inactive">Inactive</button>
                                    <button type="button" class="btn btn-sm btn-primary active" id="active">Active</button>
                                </div>
                                <input type="hidden" name="status" id="statusInput">
                            </div>

                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateAYBtn">
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
</div> -->

<!-- <script>
$(document).ready(function(){
    // Handle click on inactive button
    $('#inactive').click(function(){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'You should have atleast one active session. You can create a new one or activate another session.',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#active').addClass('active');
                $('#inactive').removeClass('active');
                toggleStatus();
            }
        });
        return false;
    });

    // Function to toggle active/inactive status and set value for status input
    function toggleStatus() {
        var activeBtn = document.getElementById('active');
        var inactiveBtn = document.getElementById('inactive');
        var statusInput = document.getElementById('statusInput');

        activeBtn.classList.toggle('btn-primary');
        activeBtn.classList.toggle('btn-default');
        inactiveBtn.classList.toggle('btn-primary');
        inactiveBtn.classList.toggle('btn-default');

        if (activeBtn.classList.contains('active')) {
            statusInput.value = 'active';
        } else {
            statusInput.value = 'inactive';
        }
    }

    // Event listeners to toggle status when buttons are clicked
    $('#status button').click(function(){
        $('#status button').removeClass('active');
        $(this).addClass('active');
        toggleStatus(); // Call toggleStatus to synchronize the visual changes
        if ($(this).attr('id') === 'active') {
            $('#statusInput').val('1'); 
        } else {
            $('#statusInput').val('0');
        }
    });

    // Set initial status value
    var statusInput = document.getElementById('statusInput');
    var activeBtn = document.getElementById('active');
    if (activeBtn.classList.contains('active')) {
        statusInput.value = 'active';
    } else {
        statusInput.value = 'inactive';
    }
});
</script>

<script>
$(document).ready(function(){
    $('.update-btn').click(function(){
       
        var ayID = $(this).data('ay-id');
        var session = $(this).data('ay-session');
        var status = parseInt($(this).data('ay-status'));
        var sem = $(this).data('ay-term');

        $('#ayID').val(ayID);
        $('#sessionUpdate').val(session);

        $('#upsem option').each(function() {
            if ($(this).val() == sem) {
                $(this).attr('selected', 'selected');
            }
        });

        if (status === 1) {
            $('#active').addClass('active btn-primary');
            $('#inactive').removeClass('active btn-primary').addClass('btn-default');
        } else {
            $('#inactive').addClass('active btn-primary');
            $('#active').removeClass('active btn-primary').addClass('btn-default');
        }

    });
});

</script>        
<script>
   // Function to handle delete button click
   function handleDeleteButtonClick(ayId, ayName) {
        Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete the session: ' + ayName,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_AY.php', true);
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
                                text: 'The session has been deleted successfully.',
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
                                text: 'Failed to delete the session: ' + response.message
                            });
                        }
                    } else {
                        // Display error message if request fails
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Failed to delete the session. Please try again later.'
                        });
                    }
                }
            };
            xhr.send('ayId=' + ayId);
            }
        });
    }


    // Attach event listener to delete buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default form submission behavior
                var ayId = this.getAttribute('data-ay-id');
                var ayName = this.closest('tr').querySelector('td:nth-child(2)').innerText; 
                handleDeleteButtonClick(ayId, ayName);
            });
        });
    });
</script> -->