<?php

require_once '../database/Database.php';
require_once '../models/Course.php';
require_once '../models/Student.php'; // Assuming you have a Student model for student data
require_once '../models/User.php'; // Assuming you have a User model for user data
session_start(); //start session para magamit yung session variable

if (!isset($_SESSION['email'])) {
    header('Location: auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Course::setConnection($conn);
Student::setConnection($conn); // Set the connection for the Student model
User::setConnection($conn); // Set the connection for the User model
User::requireRole(['Admin', 'Super-admin']);

$id = $_GET['id'];
$course = Course::find($id);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../404.php");
    exit;
}

if (!$course) {
    header("Location: ../404.php");
    exit;
}

$students = $course->student();

include '../layout/header.php';

?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 75%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-start align-items-center">
                <?php if (!empty($students)): ?>
                    <a class="btn export-btn" href=../reports/students_in_course.php?id=<?= $course->id ?>" target="_blank" style="width: 25%;">Download PDF</a>
                <?php endif; ?>
                <a href="index.php" class="cancel-btn ms-auto">&times;</a>
            </div>
        </div>
        <h2 class="details-title text-center mb-3" id="black-text"><?= $course->name ?> (<?= $course->code ?>) List</h2>
        <?php if (empty($students)): ?>
            <div class="text-center">
                <p class="text-muted">No students enrolled in this course.</p>
            </div>
        <?php else: ?>
            <div class=table-responsive>
                <table id="coursesTableDetails" class="table table-borderless text-center mx-auto">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Year Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $student->student_id ?></td>
                                <td><?= $student->name ?></td>
                                <td><?= $student->gender ?></td>
                                <td><?= $student->year_level ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../layout/footer.php'; ?>