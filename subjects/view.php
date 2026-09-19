<?php
require_once '../database/Database.php';
require_once '../models/Course.php';
require_once '../models/Student.php'; // Assuming you have a Student model for student data
require_once '../models/Subject.php'; // Assuming you have a Subject model for subject data
require_once '../models/User.php'; // Assuming you have a User model for user data
require_once '../models/Enrollment.php'; // Assuming you have an Enrollment model for enrollment data
require_once '../models/User.php';

session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Subject::setConnection($conn);
Course::setConnection($conn);
User::setConnection($conn);
Student::setConnection($conn); // Set the connection for the Student model

User::requireRole(['Admin', 'Super-admin']);

$id = $_GET['id'];
$subject = Subject::find($id);

if (!$subject || !isset($subject->id)) {
    header("Location: ../404.php");
    exit;
}

$students = $subject->students(); // Get the students enrolled in the course

include '../layout/header.php';

?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 55%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-start align-items-center">
                <a class="btn export-btn" href="../reports/subject-wise.php?id=<?= $subject->id ?>" target="_blank" style="width: 25%;">Download PDF</a>
                <a href="index.php" class="cancel-btn ms-auto">&times;</a>
            </div>
        </div>

        <h2 class="details-title text-center fw-bolder mb-3" id="text"> <?= $subject->name ?> (<?= $subject->catalog_no ?>)</h2>

        <div class="row mb-3 justify-content-center text-center">
            <div class="col-md-3">
                <label id="black-text">Course</label>
                <p><?= Course::find($subject->course_id)->name ?></p>
            </div>

            <div class="col-md-3">
                <label id="black-text">Instructor</label>
                <p><?= User::find($subject->instructor_id)->name ?></p>
            </div>

            <div class="col-md-3">
                <label id="black-text">Course and Year Level</label>
                <p><?= Course::find($subject->course_id)->code?> - <?=$subject->year_level?></p>
            </div>

            <div class="col-md-3">
                <label id="black-text">Number of Enrolled Students</label>
                <p><?= count($students) ?></p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <a href="../enrollment/create.php?subject_id=<?= $subject->id ?>" class="btn enroll-btn" style="width: 25%;">Enroll Student</a>
            </div>
            <h2 class="details-title text-center mb-3" id="black-text">List of Enrolled Students</h2>


        </div>

        <?php if (empty($students)): ?>
                <p class="text-center justify-content-center">No students found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table id="studentsTableDetails" class="table table-borderless text-center mx-auto">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Year Level</th>
                            <th>Grade</th> <!--GRADES-->
                            <th>Remarks</th> <!--GRADES-->
                            <th>Status</th> <!--ENROLLMENT-->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $student->student_id ?></td>
                                <td><?= $student->name ?></td>
                                <td><?= $student->year_level ?></td>
                                <td><?php
                                    if ($student->studentGrade($subject->id) == null) {
                                        echo 'N/A';
                                    } else {
                                        echo $student->studentGrade($subject->id)->grade;
                                    }
                                    ?></td>
                                <td><?php
                                    if ($student->studentGrade($subject->id) == null) {
                                        echo 'N/A';
                                    } else {
                                        echo $student->studentGrade($subject->id)->remarks;
                                    } ?></td>
                                <td>
                                     <?php if ($student->studentEnrollment($subject->id)->status === 'Enrolled'): ?>
                                                <div class='btn' style="background-color:rgb(66, 164, 104)">Enrolled</div>
                                            <?php elseif ($student->studentEnrollment($subject->id)->status === 'Dropped'): ?>
                                                <div class= 'btn' style="background-color:rgb(202, 89, 89)">Dropped</div></li>
                                            <?php elseif ($student->studentEnrollment($subject->id)->status === 'Completed'): ?>
                                                <div class= 'btn' style="background-color:rgb(210, 202, 44)">Completed</div></li>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="actions-dropdown d-flex justify-content-center align-items-center">
                                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a href="../enrollment/edit.php?id=<?= $student->id ?>&subject_id=<?= $subject->id ?>" class="edit-btn"><i class="bi bi-pencil-square"></i>Edit</a></li>
                                            <li><a href="../enrollment/destroy.php?student_id=<?= $student->id ?>&subject_id=<?= $subject->id ?>" class="delete-btn"><i class="bi bi-trash"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>