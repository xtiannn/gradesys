<style>
    .small-dropdown {
        padding: 0;
        min-width: 50px;
    }

    .dropdown-menu .action-item {
        padding: 5px; 
    }

    .dropdown-menu .dropdown-item i {
        font-size: 18px; 
    }

    .dropdown-menu .dropdown-item {
        padding: 5px 10px; 
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f0f0f0;
    }
</style>

<!-- elementary sections -->
<div class="tab-pane fade" id="elemSections" role="tabpanel" aria-labelledby="elemSections-tab">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex flex-column">
            <h6 class="custom-card-title">
                <i class="bi bi-folder me-2"></i>
                Sections Management
            </h6>
        </div>
        <div class="d-flex align-items-center mb-0">
            <div class="me-2"> 
                <a href="archivedSections.php?deptID=1" class="btn btn-secondary btn-sm">
                    <i class="bi bi-archive"></i>
                </a>
            </div>
            <button type="button" class="btn btn-primary btn-sm" id="btnAdd" data-bs-toggle="modal" data-bs-target="#addSectionModalElem">
                <i class="bi bi-plus-lg"></i>
                Create Section
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped datatable">
            <thead>
                <?php 
                    require_once("includes/config.php");
                    $query = "SELECT s.*, gl.*,
                    (SELECT lname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_lname,
                    (SELECT fname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_fname,
                    (SELECT gender FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_gender
                FROM sections s
                JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
                JOIN academic_year ay ON s.ayID = ay.ayID
                WHERE s.isActive = 1 AND gl.deptID = 1 AND s.ayName = ay.ayName
                ORDER BY gl.gradelvlcode ASC, s.secName ASC";
    
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);  

                    $count = 0;
                ?>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 30%;">Section</th>
                    <th style="width: 20%;">Adviser</th>
                    <th style="white-space: nowrap" class="text-center">Total Students</th>
                    <th style="width: 20%;" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($sections as $row): 
                        $lname = htmlspecialchars($row['adviser_lname']);
                        $fname = htmlspecialchars($row['adviser_fname']);
                        $gender = $row['adviser_gender'];

                        $initials = strtoupper(substr($fname, 0, 1)) . '.';


                        $prefix = ($gender === 'Female') ? 'Ms. ' : 'Mr. ';
                        $formattedAdviser = $prefix . ' ' . $lname . ' ' . $initials;
                        $secName = $row['gradelvlcode'] . ' - ' . $row['secName'];

                ?>
                    <tr>
                        <td class="text-center"><?php echo ++$count; ?>.</td> 
                        <td><?php echo htmlspecialchars(ucwords(strtolower($secName))); ?></td>
                        <td><?php echo ucwords(strtolower($formattedAdviser)); ?></td>
                        <td class="text-center">
                            <?php
                            $studentCountQuery = "SELECT COUNT(*) AS student_count
                                                FROM section_students ss
                                                WHERE ss.secID = :secID 
                                                AND ss.gradelvlID = :gradelvlID 
                                                AND ss.subjectID IS NULL";

                            $studentCountStmt = $conn->prepare($studentCountQuery);
                            $studentCountStmt->bindParam(':secID', $row['secID'], PDO::PARAM_INT);
                            $studentCountStmt->bindParam(':gradelvlID', $row['gradelvlID'], PDO::PARAM_INT);
                            $studentCountStmt->execute();

                            $studentCountResult = $studentCountStmt->fetch(PDO::FETCH_ASSOC);
                            $totalEnrolled = $studentCountResult['student_count'] ?? 0;

                            echo '<span class="' . ($totalEnrolled == 0 ? 'badge badge-danger' : '') . '">';
                            if ($totalEnrolled == 0) {
                                echo 'No students yet';
                            } else {
                                echo $totalEnrolled;
                            }
                            echo '</span>';
                        ?>
                        </td> 
                        <?php 
                            require_once("includes/functions.php");
                            $urlElemsec = [
                                'secID' => $row['secID'],
                                'gradelvlID' => $row['gradelvlID'],
                                'ayID' => $row['ayID'],
                                'deptID' => 1
                            ];
                            
                            $urlElemsec = buildUrl('manage_sec.php', $urlElemsec);
                        ?>
                        <?php 
                            require_once("includes/functions.php");
                            $paramsElemstud = [
                                'secID' => $row['secID'],
                                'gradelvlID' => $row['gradelvlID'],
                                'ayID' => $row['ayID'],
                                'facultyID' => $row['facultyID'],
                                'deptID' => 1
                            ];
                            
                            $urlElemstud = buildUrl('enrolled_students.php', $paramsElemstud);
                        ?>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <!-- Assign Faculty Button -->
                                <a href="<?php echo $urlElemsec; ?>" style="width: 50px; height: auto" class="btn btn-success btn-sm me-1" 
                                    data-bs-toggle="tooltip"
                                    title="Assign Faculty"
                                    data-sec-id="<?php echo $row['secID']; ?>" 
                                    data-sec-program="<?php echo htmlspecialchars($row['programcode'] ?? NULL); ?>">
                                    <i class="bi bi-book"></i>
                                </a>

                                <!-- Enroll Students Button -->
                                <a href="<?php echo $urlElemstud; ?>" style="width: 50px; height: auto" class="btn btn-info btn-sm me-1" 
                                    data-bs-toggle="tooltip"
                                    title="Enroll Students"
                                    data-sec-id="<?php echo $row['secID']; ?>" 
                                    data-sec-program="<?php echo htmlspecialchars($row['programcode'] ?? NULL); ?>">
                                    <i class="bi bi-person-plus"></i>
                                </a>

                                <!-- More Button with Toggle for Icons -->
                                <div class="dropdown">
                                    <button class="btn btn-link btn-sm more-actions-btn" style="width: 50px; height: auto; padding: 0;" 
                                        id="moreActionsButton" data-bs-toggle="dropdown" aria-expanded="false" title="More Actions">
                                        <i class="bi bi-three-dots-vertical" style="font-size: 20px"></i>
                                    </button>
                                    <!-- Dropdown Menu for Update and Delete (Hidden initially) -->
                                    <ul class="dropdown-menu dropdown-menu-end small-dropdown" aria-labelledby="moreActionsButton">
                                        <!-- Update Section -->
                                        <li class="action-item">
                                            <button class="btn btn-primary update-btn-elem btn-sm me-1"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#updateSectionModalElem" 
                                                data-section-id="<?php echo $row['secID']; ?>" 
                                                data-section-name="<?php echo $row['secName']; ?>" 
                                                data-section-ay="<?php echo $row['ayID']; ?>" 
                                                data-section-gradelvl="<?php echo $row['gradelvlID']; ?>" 
                                                data-faculty-id="<?php echo $row['facultyID']; ?>" 
                                                data-ay-name="<?php echo $row['ayName']; ?>">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </li>

                                        <!-- Delete Section -->
                                        <li class="action-item">
                                            <button class="btn btn-danger delete-btn btn-sm" 
                                                data-bs-toggle="tooltip"
                                                title="Delete Section"
                                                data-section-id="<?php echo $row['secID']; ?>" >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

