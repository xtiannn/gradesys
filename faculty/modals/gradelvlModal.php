
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
<div class="modal fade" id="addGradelvlModal" tabindex="-1" role="dialog" aria-labelledby="addGradelvlModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_gradelvl.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Create Grade Level</legend>
                        <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="gradelvlcode" class="form-label fw-bold">Grade Level Code:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-code-slash"></i></span>
                                </div>
                                <input type="text" class="form-control" id="gradelvlcode" name="txtGradelvlCode" placeholder="Code">
                            </div>
                        </div>
                        </div>
                        <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="gradelvlname" class="form-label fw-bold">Grade Level:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-journal-text"></i></span>
                                </div>
                                <input type="text" class="form-control" id="gradelvlname" name="txtGradelvlName" placeholder="Grade Level">
                            </div>
                        </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveGradelvlBtn">
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
<div class="modal fade" id="updateGradelvlModal" tabindex="-1" role="dialog" aria-labelledby="addGradelvlModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Floating Labels Form -->
                <form action="save_gradelvl.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Create Grade Level</legend>
                        <input type="hidden" name="gradelvlID" id="gradelvlID">
                        <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="updatecode" class="form-label fw-bold">Grade Level Code:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-code-slash"></i></span>
                                </div>
                                <input type="text" class="form-control" id="updatecode" name="txtGradelvlCode" placeholder="Code">
                            </div>
                        </div>
                        </div>
                        <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label for="updatename" class="form-label fw-bold">Grade Level:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text"><i class="bi bi-journal-text"></i></span>
                                </div>
                                <input type="text" class="form-control" id="updatename" name="txtGradelvlName" placeholder="Grade Level">
                            </div>
                        </div>
                        </div>
                    </fieldset>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateGradelvlBtn">
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
$(document).ready(function(){
    $('.updateGradelvl-btn').click(function(){
        var gradelvlID = $(this).data('gradelvl-id');
        var name = $(this).data('gradelvl-name');
        var code = $(this).data('gradelvl-code');
       
        $('#gradelvlID').val(gradelvlID);
        $('#updatecode').val(code); 
        $('#updatename').val(name); 
    });
});

</script>
<script>
    // Function to handle delete button click
    function handleDeleteButtonClick(gradelvlId, gradelvlName, rowElement) {
        Swal.fire({
            title: 'Confirmation Required',
            text: 'You are about to delete the ' + gradelvlName,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('delete_gradelvl.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'gradelvlId=' + encodeURIComponent(gradelvlId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The grade level has been deleted successfully.',
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
                            text: 'Failed to delete the ' + data.message
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to delete the grade level. Please try again later.'
                    });
                });
            }
        });
    }

    // Attach event listener to delete buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default form submission behavior
                const gradelvlId = this.getAttribute('data-gradelvl-id');
                const gradelvlName = this.closest('tr').querySelector('td:nth-child(3)').innerText; // Get the grade level name from the row
                const rowElement = this.closest('tr'); // Get the row element
                handleDeleteButtonClick(gradelvlId, gradelvlName, rowElement);
            });
        });
    });
</script>




