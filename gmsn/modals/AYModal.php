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
                                    <input type="hidden" name="startYear" id="startYear">
                                    <input type="hidden" name="endYear" id="endYear">
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
    $('#yearPickerButton').datepicker({
        format: "yyyy",
        startView: "years",
        minViewMode: "years",
        autoclose: true,
    }).on('changeDate', function(e) {
        var selectedYear = e.date.getFullYear();
        $('#sessionUpdate').val(selectedYear + ' - ' + (selectedYear + 1));
        
        // Correctly set values for startYear and endYear fields
        $('#startYear').val(selectedYear);
        $('#endYear').val(selectedYear + 1);
    });

    $('.updateAY-btn').click(function(){
        var ayID = $(this).data('ay-id');
        var start = $(this).data('ay-start');
        var end = $(this).data('ay-end');
        var sem = $(this).data('ay-term');

        $('#ayID').val(ayID);
        $('#sessionUpdate').val(start + ' - ' + end);

        // Set startYear and endYear fields when editing
        $('#startYear').val(start);
        $('#endYear').val(end);

        $('#upsem').val(sem).change();
        $('.selectpicker').selectpicker('refresh');

        $('#updateAYModal').modal('show');
    });
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if 'alert' query parameter is set
    const urlParams = new URLSearchParams(window.location.search);
    const alertType = urlParams.get('alert');

    if (alertType) {
        let title, text, icon;
        let timer = 0; // Default to no timer

        switch (alertType) {
            case 'sem-changed':
                title = 'Success!';
                text = 'Semester Updated Successfully';
                icon = 'success';
                timer = 5000; 
                break;
            case 'success':
                title = 'Success!';
                text = 'The A.Y. Updated Successfully';
                icon = 'success';
                timer = 5000; 
                break;
            case 'no-changes':
                title = 'Notice!';
                text = 'No changes were made when updating session.';
                icon = 'info';
                break;
            case 'db_error':
                title = 'Error!';
                text = 'A database error occurred.';
                icon = 'error';
                break;
            default:
                return; 
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonText: 'OK',
            timer: timer,
            timerProgressBar: true
        }).then(() => {
            // Clear query parameters from the URL
            const url = new URL(window.location.href);
            url.searchParams.delete('alert');
            url.searchParams.delete('message'); // Remove any other parameters if needed
            window.history.replaceState({}, document.title, url.href);
        });
    }
});
</script>

