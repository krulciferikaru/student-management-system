<?php
require_once '../models/Student.php';
require_once '../models/Course.php';
require_once '../models/Subject.php';
require_once '../models/User.php';
require_once '../models/Grade.php';
require_once '../models/Enrollment.php';
require_once '../database/Database.php';

session_start();

if (!isset($_SESSION['email'])) {
    header('Location: auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);
Course::setConnection($conn);
Subject::setConnection($conn);
User::setConnection($conn);
Grade::setConnection($conn);
Enrollment::setConnection($conn);
User::requireRole(['Instructor']);

$user = User::findEmail($_SESSION['email']);

$studentCount = count(Student::all());
$courseCount = count(Course::all());
$subjectCount = count(Subject::all());
$instructorCount = count(User::where('role', '=', 'Instructor'));
$adminCount = count(User::where('role', '=', 'Admin'));

$assignedSubjectCount = count($user->subjects());
$enrolledStudentCount = $user->StudentsEnrolled();
$pendingGradingTaskCount = $user->pendingGradingTasks();

require_once '../layout/header.php';
?>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/dashboard.css">
<div class="container-fluid dashboard-container d-flex flex-column align-items-center">
    <!-- Dashboard Title -->
    <div class=" row w-100 mb-4 justify-content-start">
        <h2 id="blue-text">Dashboard</h2>
    </div>

    <!-- First Row (Welcome Message and User Profile) -->
    <div class="row w-100 align-items-center justify-content-center container-fluid flex-wrap mb-4">
        <!-- Welcome Message -->
        <div class="col-12 col-lg-8 d-flex justify-content-center mb-4 mb-md-0">
            <div id="welcome-container" class="d-flex flex-column justify-content-center text-start">
                <h3 id="black-text">Welcome,</h3>
                <h1 id="blue-text"><?= strtoupper($user->name) ?></h1>
                <p class="lead" id="">Warm greetings, Instructor! Manage and guide your students with ease.</p>
            </div>
        </div>
        <!-- User Profile -->
        <div class="col-12 col-lg-4 d-flex justify-content-center mb-4 mb-md-0">
            <div id="user-profile-container" class="d-flex flex-column justify-content-center align-items-center text-center">
                <img class="pfp-picture img-fluid" src="../css/images/Profile Picture.png" style="width: 225px; height: auto;">
                <div class="mt-3">
                    <h4 id="black-text"><?= $user->name ?></h4>
                    <h6 id="blue-text"><?= strtoupper($user->role) ?></h6>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row (Count Containers) -->
    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-3 g-4 container-fluid transition-all">

        <div class="col p-2">
            <div id="assigned-subject-container" class="count-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between w-100">
                    <div class="count-details text-center text-md-start">
                        <h6 id="black-text">Assigned Subjects</h6>
                        <h3 id="black-text"><?= $assignedSubjectCount > 0 ? $assignedSubjectCount : "None" ?></h3>
                    </div>
                    <div>
                        <i class="bi bi-book"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col p-2">
            <div id="enrolled-student-container" class="count-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between w-100">
                    <div class="count-details text-center text-md-start">
                        <h6 id="black-text">Enrolled Students</h6>
                        <h3 id="black-text"><?= $enrolledStudentCount > 0 ? $enrolledStudentCount : "None" ?></h3>
                    </div>
                    <div>
                        <i class="bi bi-person"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col p-2">
            <div id="pending-grade-task-container" class="count-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between w-100">
                    <div class="count-details text-center text-md-start">
                        <h6 id="black-text">Pending Grade Tasks</h6>
                        <h3 id="black-text"><?= $pendingGradingTaskCount > 0 ? $pendingGradingTaskCount : "None" ?></h3>
                    </div>
                    <div>
                        <i class="bi bi-journal"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>