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

<!-- Modal Form fac-->
<div class="modal fade" id="addFacultyModal" tabindex="-1" role="dialog" aria-labelledby="addFacultyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="save_faculty.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Add Faculty</legend>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="txtuserlnamefac" class="form-label fw-bold">Full Name:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <input type="text" class="form-control me-5" id="txtuserlnamefac" name="txtFacultylname" placeholder="Last Name" required>
                                    <input type="text" class="form-control me-5" id="txtuserfnamefac" name="txtFacultyfname" placeholder="First Name" required>
                                    <input type="text" class="form-control" id="txtusermnamefac" name="txtFacultymname" placeholder="Middle Name">
                                    <div class="invalid-feedback">Please provide a full name.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="userIDfac" class="form-label fw-bold">User ID:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="userIDfac" name="txtFacultyNumber" placeholder="User ID" required>
                                    <div class="invalid-feedback">Please provide a user ID.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="txtcontactfac" class="form-label fw-bold">Contact No.:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    </div>
                                    <input type="number" class="form-control" name="txtFacultyContactNum" id="txtcontactfac" placeholder="9-digits Contact No." required>
                                    <div class="invalid-feedback">Please provide a 9-digit contact number.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="selGenderfac" class="form-label fw-bold">Gender:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                    </div>
                                    <select class="form-select form-control" id="selGenderfac" name="txtFacultyGender" required>
                                    <option selected disabled value>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a gender.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="txtemailfac" class="form-label fw-bold">Email:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="txtemailfac" name="txtemail" placeholder="email@example.com" required>
                                    <div class="invalid-feedback">Please provide an email address.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="userPasswordfac" class="form-label fw-bold">Password:</label> <small><input type="checkbox" name="defaultPass" id="defaultPassfac"> <label for="defaultPassfac">Use Contact No. as default Password</label></small>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="userPasswordfac" name="txtPassword" placeholder="Password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleUserPasswordfac">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">Please provide a password.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="userConPasswordfac" class="form-label fw-bold">Confirm Password:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="userConPasswordfac" name="txtConfirmPassword" placeholder="Confirm Password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConUserPasswordfac">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">Please confirm your password.</div>
                                </div>
                            </div>
                            <small id="userPasswordHelpfac" class="form-text"></small>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveFacultyBtn">
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
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInputfac = document.getElementById('userPasswordfac');
        const confirmPasswordInputfac = document.getElementById('userConPasswordfac');
        const passwordHelpTextfac = document.getElementById('userPasswordHelpfac');
        const toggleUserPasswordfac = document.getElementById('toggleUserPasswordfac');
        const toggleConUserPasswordfac = document.getElementById('toggleConUserPasswordfac');
        const defaultPassCheckboxfac = document.getElementById('defaultPassfac');
        const contactNumberInputfac = document.getElementById('txtcontactfac');

        function updatePasswordHelpTextfac() {
            if (passwordInputfac.value === confirmPasswordInputfac.value) {
                passwordHelpTextfac.textContent = 'Passwords match';
                passwordHelpTextfac.classList.remove('text-danger');
                passwordHelpTextfac.classList.add('text-success');
            } else {
                passwordHelpTextfac.textContent = 'Passwords do not match';
                passwordHelpTextfac.classList.remove('text-success');
                passwordHelpTextfac.classList.add('text-danger');
            }
        }

        function togglePasswordVisibilityfac(input, button) {
            if (input.type === 'password') {
                input.type = 'text';
                button.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                input.type = 'password';
                button.innerHTML = '<i class="bi bi-eye"></i>';
            }
        }

        toggleUserPasswordfac.addEventListener('click', function() {
            togglePasswordVisibilityfac(passwordInputfac, toggleUserPasswordfac);
        });

        toggleConUserPasswordfac.addEventListener('click', function() {
            togglePasswordVisibilityfac(confirmPasswordInputfac, toggleConUserPasswordfac);
        });

        passwordInputfac.addEventListener('input', updatePasswordHelpTextfac);
        confirmPasswordInputfac.addEventListener('input', updatePasswordHelpTextfac);

        defaultPassCheckboxfac.addEventListener('change', function() {
            if (defaultPassCheckboxfac.checked) {
                // Set contact number as default password
                passwordInputfac.value = contactNumberInputfac.value;
                confirmPasswordInputfac.value = contactNumberInputfac.value;
            } else {
                // Clear password fields
                passwordInputfac.value = '';
                confirmPasswordInputfac.value = '';
            }

            // Update password match status
            updatePasswordHelpTextfac();
        });

        contactNumberInputfac.addEventListener('input', function() {
            if (defaultPassCheckboxfac.checked) {
                // Update password fields when contact number changes
                passwordInputfac.value = contactNumberInputfac.value;
                confirmPasswordInputfac.value = contactNumberInputfac.value;

                // Update password match status
                updatePasswordHelpTextfac();
            }
        });
    });
</script>


<!-- Update Form fac-->
<div class="modal fade" id="updateFacultyModal" tabindex="-1" role="dialog" aria-labelledby="addFacultyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="save_faculty.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">Faculty Account Registration</legend>
                        <input type="text" name="facultyID" id="facultyID">
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="updatenum" class="form-label fw-bold">Faculty ID:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="updatenum" name="txtFacultyNumber" placeholder="Faculty ID" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="updatelname" class="form-label fw-bold">Full Name:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="updatelname" name="txtFacultylname" placeholder="Last Name" required>
                                    <input type="text" class="form-control" id="updatefname" name="txtFacultyfname" placeholder="First Name" required>
                                    <input type="text" class="form-control" id="updatemname" name="txtFacultymname" placeholder="Middle Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                            <label for="updategender" class="form-label fw-bold">Gender:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                </div>
                                <select class="form-select form-control selectpicker" id="updategender" name="txtFacultyGender" required>
                                    <option selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                            <label for="updatecontact" class="form-label fw-bold">Contact No.:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                </div>
                                <input type="number" class="form-control" name="txtFacultyContactNum" id="updatecontact" placeholder="Contact Number" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="updateemail" class="form-label fw-bold">Email:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="updateemail" name="txtemail" placeholder="Email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="updatepass" class="form-label fw-bold">Password:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="updatepass" name="txtPassword" placeholder="Password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordVisibility">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="updateconpass" class="form-label fw-bold">Confirm Password:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="updateconpass" name="txtConfirmPassword" placeholder="Confirm Password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="updatetoggleConfirmPasswordVisibility">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <small id="updatepasswordHelp" class="form-text"></small>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="updateFacultyBtn">
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


<!-- Modal Form admin -->
<div class="modal fade" id="createUser" tabindex="-1" role="dialog" aria-labelledby="createUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body modal-lg">
                <form action="save_faculty.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <fieldset class="border p-4 rounded mb-4">
                        <legend class="mb-4">User Account Registration</legend>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="selType" class="form-label fw-bold">User Type:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    </div>
                                    <select class="form-select form-control" id="selType" name="selType" required>
                                    <option selected disabled value>Select User Type</option>
                                    <?php
                                    try {
                                        $query = "SELECT * FROM user_type";

                                        $stmt = $conn->query($query);

                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row['typeID'] . '">' . $row['userType'] . '</option>';
                                        }
                                    } catch (PDOException $e) {
                                        echo '<option disabled>Error fetching User Types</option>';
                                    }
                                    ?>
                                    </select>
                                    <div class="invalid-feedback">Please select a user type.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="userID" class="form-label fw-bold">User ID:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="userID" name="txtuserID" placeholder="User ID" required>
                                    <div class="invalid-feedback">Please provide a user ID.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label for="txtuserlname" class="form-label fw-bold">Full Name:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    </div>
                                    <input type="text" class="form-control me-5" id="txtuserlname" name="txtuserlname" placeholder="Last Name" required>
                                    <input type="text" class="form-control me-5" id="txtuserfname" name="txtuserfname" placeholder="First Name" required>
                                    <input type="text" class="form-control" id="txtusermname" name="txtusermname" placeholder="Middle Name">
                                    <div class="invalid-feedback">Please provide a full name.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <label for="selGender" class="form-label fw-bold">Gender:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                                    </div>
                                    <select class="form-select form-control" id="selGender" name="selGender" required>
                                    <option selected disabled value>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a gender.</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="txtcontact" class="form-label fw-bold">Contact No.:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    </div>
                                    <input type="number" class="form-control" name="txtcontact" id="txtcontact" placeholder="9-digits Contact No." required>
                                    <div class="invalid-feedback">Please provide a 9-digit contact number.</div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="txtemail" class="form-label fw-bold">Email:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="txtemail" name="txtemail" placeholder="email@example.com" required>
                                    <div class="invalid-feedback">Please provide an email address.</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="userPassword" class="form-label fw-bold">Password:</label> <small><input type="checkbox" name="defaultPass" id="defaultPass"> <label for="defaultPass">Use Contact No. as default Password</label></small>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleUserPassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">Please provide a password.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="userConPassword" class="form-label fw-bold">Confirm Password:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="userConPassword" name="userConPassword" placeholder="Confirm Password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConUserPassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <div class="invalid-feedback">Please confirm your password.</div>
                                </div>
                            </div>
                            <small id="userPasswordHelp" class="form-text"></small>
                        </div>
                    </fieldset>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary me-md-2" name="saveUserBtn">
                        <i class="bi bi-person-plus"></i> Register
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
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('userPassword');
        const confirmPasswordInput = document.getElementById('userConPassword');
        const passwordHelpText = document.getElementById('userPasswordHelp');
        const toggleUserPassword = document.getElementById('toggleUserPassword');
        const toggleConUserPassword = document.getElementById('toggleConUserPassword');
        const defaultPassCheckbox = document.getElementById('defaultPass');
        const contactNumberInput = document.getElementById('txtcontact');

        function updatePasswordHelpText() {
            if (passwordInput.value === confirmPasswordInput.value) {
                passwordHelpText.textContent = 'Passwords match';
                passwordHelpText.classList.remove('text-danger');
                passwordHelpText.classList.add('text-success');
            } else {
                passwordHelpText.textContent = 'Passwords do not match';
                passwordHelpText.classList.remove('text-success');
                passwordHelpText.classList.add('text-danger');
            }
        }

        function togglePasswordVisibility(input, button) {
            if (input.type === 'password') {
                input.type = 'text';
                button.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                input.type = 'password';
                button.innerHTML = '<i class="bi bi-eye"></i>';
            }
        }

        toggleUserPassword.addEventListener('click', function() {
            togglePasswordVisibility(passwordInput, toggleUserPassword);
        });

        toggleConUserPassword.addEventListener('click', function() {
            togglePasswordVisibility(confirmPasswordInput, toggleConUserPassword);
        });

        passwordInput.addEventListener('input', updatePasswordHelpText);
        confirmPasswordInput.addEventListener('input', updatePasswordHelpText);

        defaultPassCheckbox.addEventListener('change', function() {
            if (defaultPassCheckbox.checked) {
                // Set contact number as default password
                passwordInput.value = contactNumberInput.value;
                confirmPasswordInput.value = contactNumberInput.value;
            } else {
                // Clear password fields
                passwordInput.value = '';
                confirmPasswordInput.value = '';
            }

            // Update password match status
            updatePasswordHelpText();
        });

        contactNumberInput.addEventListener('input', function() {
            if (defaultPassCheckbox.checked) {
                // Update password fields when contact number changes
                passwordInput.value = contactNumberInput.value;
                confirmPasswordInput.value = contactNumberInput.value;

                // Update password match status
                updatePasswordHelpText();
            }
        });

        // Form validation
        const form = document.querySelector('.needs-validation');

        form.addEventListener('submit', function(event) {
            const selectElements = form.querySelectorAll('select[required]');
            let isFormValid = true;

            selectElements.forEach(function(select) {
                if (select.value === '' || select.value === null) {
                    select.classList.add('is-invalid');
                    isFormValid = false;
                } else {
                    select.classList.remove('is-invalid');
                    select.classList.add('is-valid');
                }
            });

            if (!form.checkValidity() || !isFormValid) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    });
</script>




<script>
$(document).ready(function(){
    $('.update-btn').click(function(){
        var facultyID = $(this).data('faculty-id');
        var lname = $(this).data('faculty-lname');
        var fname = $(this).data('faculty-fname');
        var mname = $(this).data('faculty-mname');
        var gender = $(this).data('faculty-gender');
        var email = $(this).data('faculty-email');
        var contact = $(this).data('faculty-contact');
        var defaultPassword = $(this).data('faculty-default-password'); // Assuming this is the default password
        var facultyNum = $(this).data('faculty-facultyNum');

        $('#facultyID').val(facultyID);
        $('#updatelname').val(lname); 
        $('#updatefname').val(fname); 
        $('#updatemname').val(mname); 
        $('#updateemail').val(email); 
        $('#updatecontact').val(contact); 
        $('#updatepass').val(defaultPassword); // Populate with default password
        $('#updateconpass').val(defaultPassword); // Populate with default password
        $('#updatenum').val(facultyNum); 

        $('#updategender option').each(function() {
            if ($(this).val() == gender) {
                $(this).attr('selected', 'selected');
            }
        });

        $('#updategender').trigger('change');
    });
});

</script>


<script>
   // Function to handle delete button click
   function handleDeleteButtonClick(facultyID, facultyName) {
        Swal.fire({
        title: 'Confirmation Required!',
        text: 'You are about to delete the Faculty: ' + facultyName,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
        }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_faculty.php', true);
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
                                text: 'The faculty level has been deleted successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Deletion Failed!',
                                text: response.message
                            });
                        }
                    } else {
                        // Display error message if request fails
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete the faculty level. Please try again later.'
                        });
                    }
                }
            };
            xhr.send('facultyID=' + facultyID); // Send the faculty level ID to the server
            }
        });
    }


    // Attach event listener to delete buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default form submission behavior
                var facultyID = this.getAttribute('data-faculty-id');
                var facultyName = this.closest('tr').querySelector('td:nth-child(3)').innerText; // Get the faculty name from the row
                handleDeleteButtonClick(facultyID, facultyName);
            });
        });
    });
</script>