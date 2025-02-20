
    <!-- senior high table sections -->
    <div class="tab-pane fade" id="shsSections" role="tabpanel" aria-labelledby="shsSections-tab">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column">
                <h6 class="custom-card-title">
                    <i class="bi bi-folder me-2"></i>
                    Sections Management
                </h6>
            </div>
            <div class="d-flex align-items-center mb-0">
                <div class="me-2"> 
                    <a href="archivedSections.php?deptID=3" class="btn btn-secondary btn-sm">
                        <i class="bi bi-archive"></i>
                    </a>
                </div>
                <button type="button" class="btn btn-primary btn-sm" id="btnAdd" data-bs-toggle="modal" data-bs-target="#addSectionModal">
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
                    $query = "SELECT s.*, gl.*, s.ayName, sm.*, p.*,
                        (SELECT lname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_lname,
                        (SELECT fname FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_fname,
                        (SELECT gender FROM faculty f WHERE f.facultyID = s.facultyID) as adviser_gender
                    FROM sections s
                    JOIN grade_level gl ON s.gradelvlID = gl.gradelvlID
                    JOIN programs p ON s.programID = p.programID
                    JOIN academic_year ay ON s.ayID = ay.ayID
                    JOIN semester sm ON s.semID = sm.semID
                    WHERE s.isActive = 1 AND gl.deptID = 3 AND (s.ayName = ay.ayName AND s.semID = :activeSem)
                    ORDER BY p.programcode ASC, gl.gradelvlcode ASC, s.secName ASC";
                    
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':activeSem', $activeSem, PDO::PARAM_STR);
                    $stmt->execute();
                    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $count = 0;
                    ?>
                    <tr>
                        <th style="width: 20px" class="text-center">#</th>
                        <!-- <th style="width: 100px" class="text-center">A.Y.</th>
                        <th style="width: 100px" class="text-center">Term</th> -->
                        <th style="width: 100px" class="text-center">Program</th>
                        <th>Section</th>
                        <th>Adviser</th>
                        <th class="text-center">Total Students</th>
                        <th style="width: 100px" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sections as $row): 
                        $lname = htmlspecialchars($row['adviser_lname']);
                        $fname = htmlspecialchars($row['adviser_fname']);
                        $gender = htmlspecialchars($row['adviser_gender']);
                        
                        $initials = strtoupper(substr($fname, 0, 1)) . '.';
                        $prefix = ($gender === 'Female') ? 'Ms. ' : 'Mr. ';
                        $formattedAdviser = $prefix . ' ' . $lname . ' ' . $initials;

                        $secName = $row['gradelvlcode'] . ' - ' . $row['secName'];
                    ?>
                        <tr>
                            <td class="text-center"><?php echo ++$count; ?>.</td>
                            <!-- <td style="white-space: nowrap" class="text-center"><?php //echo htmlspecialchars($row['ayName']); ?></td>
                            <td class="text-center"><?php //echo htmlspecialchars($row['semCode']); ?></td> -->
                            <td class="text-center"><?php echo htmlspecialchars($row['programcode']); ?></td>
                            <td><?php echo htmlspecialchars($row['gradelvlcode'] . ' - ' . ucwords(strtolower($row['secName']))); ?></td>
                            <td><?php echo ucwords(strtolower($formattedAdviser)); ?></td>
                            <td class="text-center">
                                <?php
                                $studentCountQuery = "SELECT COUNT(*) AS student_count
                                                    FROM section_students ss
                                                    WHERE ss.secID =  :secID
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
                                $paramsSHSsec = [
                                    'secID' => $row['secID'],
                                    'gradelvlID' => $row['gradelvlID'],
                                    'ayID' => $row['ayID'],
                                    'programID' => $row['programID'],
                                    'semID' => $row['semID'],
                                    'deptID' => 3
                                ];
                                
                                $urlSHSsec = buildUrl('manage_sec.php', $paramsSHSsec);

                                require_once("includes/functions.php");
                                $paramsSHSstud = [
                                    'secID' => $row['secID'],
                                    'gradelvlID' => $row['gradelvlID'],
                                    'ayID' => $row['ayID'],
                                    'facultyID' => $row['facultyID'],
                                    'programID' => $row['programID'],
                                    'semID' => $row['semID'],
                                    'deptID' => 3
                                ];
                                
                                $urlSHSstud = buildUrl('enrolled_students.php', $paramsSHSstud);
                                ?>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center">

                                    <a href="<?php echo $urlSHSsec?>"  style="width: 50px; height: auto" class="btn btn-success btn-sm me-1"  class="btn btn-success btn-sm me-1" 
                                        data-bs-toggle="tooltip"
                                        title="Assign Faculty"
                                        data-sec-id="<?php echo $row['secID']; ?>" 
                                        data-sec-program="<?php echo htmlspecialchars($row['programcode'] ?? NULL); ?>">
                                        <i class="bi bi-book" ></i>
                                    </a>

                                    <a href="<?php echo $urlSHSstud?>"  style="width: 50px; height: auto" class="btn btn-info btn-sm me-1" 
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
                                            <button class="btn btn-primary update-btn-shs btn-sm me-1" href="#" data-bs-toggle="modal" data-bs-target="#updateSectionModal"
                                                data-section-id="<?php echo $row['secID']; ?>"
                                                data-section-name="<?php echo htmlspecialchars($row['secName']); ?>"
                                                data-section-ay="<?php echo htmlspecialchars($row['ayID']); ?>"
                                                data-section-program="<?php echo htmlspecialchars($row['programID']); ?>"
                                                data-section-sem="<?php echo htmlspecialchars($row['semID']); ?>"
                                                data-section-adviser="<?php echo htmlspecialchars($row['facultyID']); ?>"
                                                data-section-gradelvl="<?php echo htmlspecialchars($row['gradelvlID']); ?>"
                                                data-ay-Name="<?php echo htmlspecialchars($row['ayName']); ?>"
                                                data-sem-Name="<?php echo htmlspecialchars($row['semName']); ?>"
                                                data-sem-id="<?php echo htmlspecialchars($row['semID']); ?>"
                                                >
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            </li>

                                            <!-- Delete Section -->
                                            <li class="action-item">
                                                <button class="btn btn-danger delete-btn btn-sm" 
                                                    data-bs-toggle="tooltip"
                                                    title="Delete Section"
                                                    data-section-id="<?php echo $row['secID']; ?>" 
                                                    data-section-program="<?php echo htmlspecialchars($row['programcode']); ?>">
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