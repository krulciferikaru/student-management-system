<?php
require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/Subject.php';
require_once '../models/User.php';
require_once '../models/Course.php';
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);
User::setConnection($conn);

User::requireRole(['Admin', 'Super-admin']);

$student_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : null;

$student = Student::find($student_id);

$subjects = $student->subjectsEnrolled();

if (!$student_id || !$student) {
    header('Location: ../404.php');
    exit;
}

include '../layout/header.php';
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 55%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <input type="hidden" name="student_id" value="<?= $student->id ?>">
                <a href="../reports/generate_pdf.php?student_id=<?= $student->id ?>" class="btn export-btn" target="_blank">Download PDF</a>
                <a href="index.php" class="cancel-btn ms-auto fs-2 text-decoration-none">&times;</a>
            </div>
        </div>

        <div class="row align-items-center">
            <!-- Image Column -->
            <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                <img class="pfp-picture img-fluid" src="../css/images/Profile Picture.png" />
            </div>

            <!-- Details Column -->
            <div class="col-12 col-md-8 student-details justify-content-center text-center text-md-start">
                <div class="row">
                    <div class="col-12 col-md-6 mb-2">
                        <label id="black-text">Student ID:</label>
                        <p><?= $student->student_id ?></p>

                        <label id="black-text">Gender:</label>
                        <p><?= $student->gender ?></p>

                        <label id="black-text">Course:</label>
                        <p><?= $student->course()->name ?></p>
                    </div>

                    <div class="col-12 col-md-6 mb-2">
                        <label id="black-text">Full Name:</label>
                        <p><?= $student->name ?></p>

                        <label id="black-text">Date of Birth:</label>
                        <p><?= $student->birthdate ?></p>

                        <label id="black-text">Year Level:</label>
                        <p><?= $student->year_level ?></p>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mb-3">
            <h2 class="details-title text-center fw-bolder mt-3 mb-3" id="text">List of Enrolled Subjects</h2>
        </div>

        <?php if (empty($subjects)): ?>
            <p colspan="4" class="text-center">No subjects enrolled yet.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table id="studentsEnrolledTable" class="text-center table">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Year</th> <!-- Moved Year Column before Instructor -->
                            <th>Semester</th> <!-- Moved Semester Column before Instructor -->
                            <th>Instructor</th> <!-- Moved Instructor Column after Year and Semester -->
                            <th>Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?= $subject->code ?></td>
                                <td><?= $subject->name ?></td>
                                <td><?= $subject->year_level ?></td> <!-- Display Year -->
                                <td><?= $subject->semester ?></td> <!-- Display Semester -->
                                <td><?= $subject->instructor()->name ?></td> <!-- Display Instructor -->
                                <?php $grade = $student->studentGrade($subject->id); ?>
                                <td><?= $grade ? $grade->grade : 'Pending' ?></td>
                                <td>
                                    <?= $grade ? $grade->remarks : 'Pending' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../layout/footer.php'; ?>