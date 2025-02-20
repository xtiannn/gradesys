
<style>
/* General modal styling */
.modal-content {
    border-radius: 10px;
    border: 1px solid #dee2e6;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Modal header */
.modal-header {
    background-color: #007bff;
    color: #fff;
    border-bottom: none;
    border-radius: 10px 10px 0 0;
    padding: 1rem 1.5rem;
}

/* Modal body */
.modal-body {
    padding: 1.5rem;
    font-family: Arial, sans-serif;
}

/* Section titles in modal body */
.modal-body h5 {
    margin-top: 1rem;
    color: #6c757d;
    text-transform: uppercase;
    font-size: 0.9rem;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 5px;
}

/* Table styling for displayed info */
.table-modal {
    width: 100%;
    margin-top: 1rem;
    border-collapse: collapse;
}

.table-modal th,
.table-modal td {
    padding: 0.5rem;
    text-align: left;
    vertical-align: top;
}

.table-modal th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.table-modal tr:nth-child(even) {
    background-color: #f2f2f2;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-dialog {
        margin: 1rem;
    }

    .modal-content {
        border-radius: 5px;
    }
}
</style>

<div class="modal fade" id="viewStudentInfo" tabindex="-1" role="dialog" aria-labelledby="viewStudentInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewStudentInfoLabel">Student Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <h5>Basic Information</h5>
                <div class="student-photo">
                    <img src="uploads/photo/" alt="Student Photo" id="studentPhoto" class="img-fluid" style="max-width: 150px; border-radius: 8px;">
                </div>

                <!-- Basic Information Section -->
                <h5>Basic Information</h5>
                <table class="table-modal">
                    <tr>
                        <th>Learner Reference Number:</th>
                        <td id="lrn"></td>
                    </tr>
                    <tr>
                        <th>Full Name:</th>
                        <td id="fullName"></td>
                    </tr>
                    <tr>
                        <th>Age:</th>
                        <td id="age"></td>
                    </tr>
                    <tr>
                        <th>Gender:</th>
                        <td id="gender"></td>
                    </tr>
                    <tr>
                        <th>Date of Birth:</th>
                        <td id="dob"></td>
                    </tr>
                    <tr>
                        <th>Nationality:</th>
                        <td id="nationality"></td>
                    </tr>
                    <tr>
                        <th>Religion:</th>
                        <td id="religion"></td>
                    </tr>
                    <tr>
                        <th>Contact:</th>
                        <td id="contact"></td>
                    </tr>
                    <tr>
                        <th>Place of Birth:</th>
                        <td id="pob"></td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td id="address"></td>
                    </tr>
                </table>


                <!-- Parent Information Section -->
                <h5>Father's Information</h5>
                <table class="table-modal">
                    <tr>
                        <th>Name:</th>
                        <td id="ffullName"></td>
                    </tr>
                    <tr>
                        <th>Age:</th>
                        <td id="fage"></td>
                    </tr>
                    <tr>
                        <th>Contact:</th>
                        <td id="fcontact"></td>
                    </tr>
                    <tr>
                        <th>Occupation:</th>
                        <td id="foccu"></td>
                    </tr>
                    <tr>
                        <th>Education:</th>
                        <td id="feduc"></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td id="femail"></td>
                    </tr>
                    <tr>
                        <th>Office Address:</th>
                        <td id="foffice"></td>
                    </tr>
                    <tr>
                        <th>Home Address:</th>
                        <td id="faddress"></td>
                    </tr>
                </table>


                <h5>Mother's Information</h5>
                <table class="table-modal">
                    <tr>
                        <th>Name:</th>
                        <td id="mfullName"></td>
                    </tr>
                    <tr>
                        <th>Age:</th>
                        <td id="mage"></td>
                    </tr>
                    <tr>
                        <th>Contact:</th>
                        <td id="mcontact"></td>
                    </tr>
                    <tr>
                        <th>Occupation:</th>
                        <td id="moccu"></td>
                    </tr>
                    <tr>
                        <th>Education:</th>
                        <td id="meduc"></td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td id="maddress"></td>
                    </tr>
                    <tr>
                        <th>Office Address:</th>
                        <td id="moffice"></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td id="memail"></td>
                    </tr>
                </table>


                <!-- Guardian Information Section -->
                <h5>Guardian Information</h5>
                <table class="table-modal">
                    <tr>
                        <th>Name:</th>
                        <td id="gfullName"></td>
                    </tr>
                    <tr>
                        <th>Relationship:</th>
                        <td id="relationship"></td>
                    </tr>
                    <tr>
                        <th>Contact:</th>
                        <td id="gcontact"></td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td id="gaddress"></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
</div>



<script>
    document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', function () {
        const studentId = this.getAttribute('data-student-id');
        
        // Fetch student data via AJAX
        fetch(`fetch/getStudentInfo.php?studID=${studentId}`)
            .then(response => response.json())
            .then(data => {
                // Populate modal fields with fetched data
                document.getElementById('studentPhoto').src = data.photo ? 'uploads/photo/' + data.photo : 'assets/img/user.png';
                document.getElementById('lrn').textContent = data.lrn || '';
                document.getElementById('fullName').innerText = `${data.lname || ''}, ${data.fname || ''} ${data.mname || ''}`;
                document.getElementById('religion').textContent = data.religion || '';
                document.getElementById('age').textContent = data.age || '';
                document.getElementById('nationality').textContent = data.age || '';
                document.getElementById('gender').textContent = data.gender || '';
                document.getElementById('dob').textContent = data.dob || '';
                document.getElementById('contact').textContent = data.contact || '';
                document.getElementById('pob').textContent = data.pob || '';
                document.getElementById('address').textContent = data.address || '';
 

                // Fill father's info
                document.getElementById('ffullName').innerText = `${data.flname || ''}, ${data.ffname || ''} ${data.fmname || ''}`;
                document.getElementById('fcontact').textContent = data.fcontact || '';
                document.getElementById('fage').textContent = data.fage || '';
                document.getElementById('foccu').textContent = data.foccu || '';
                document.getElementById('feduc').textContent = data.feduc || '';
                document.getElementById('foffice').textContent = data.foffice || '';
                document.getElementById('femail').textContent = data.femail || '';
                document.getElementById('faddress').textContent = data.faddress || '';

                // Fill mother's info
                document.getElementById('mfullName').innerText = `${data.mlname || ''}, ${data.mfname || ''} ${data.mmname || ''}`;
                document.getElementById('mcontact').textContent = data.mcontact || '';
                document.getElementById('mage').textContent = data.mage || '';
                document.getElementById('mcontact').textContent = data.mcontact || '';
                document.getElementById('moccu').textContent = data.moccu || '';
                document.getElementById('moffice').textContent = data.moffice || '';
                document.getElementById('meduc').textContent = data.meduc || '';
                document.getElementById('memail').textContent = data.memail || '';
                document.getElementById('maddress').textContent = data.maddress || '';
                // Fill guardian's info
                document.getElementById('gfullName').innerText = `${data.glname}, ${data.mgfname} ${data.gmname || ''}`;
                document.getElementById('gcontact').textContent = data.gcontact || '';
                document.getElementById('gcontact').textContent = data.gcontact || '';
                document.getElementById('relationship').textContent = data.relationship || '';
                document.getElementById('gaddress').textContent = data.gaddress || '';

                // Fill status
            })
            .catch(error => {
                console.error('Error fetching student data:', error);
            });
    });
});

</script>



