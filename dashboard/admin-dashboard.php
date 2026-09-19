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
User::requireRole(['Admin']);

$user = User::findEmail($_SESSION['email']);

$studentCount = count(Student::all());
$courseCount = count(Course::all());
$subjectCount = count(Subject::all());
$instructorCount = count(User::where('role', '=', 'Instructor'));

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
                <p class="lead" id="">Warm greetings, Administrator! You may now oversee all system operations.</p>
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
    <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-4 g-4 container-fluid transition-all">

        <div class="col p-2">
            <div id="student-container" class="count-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between w-100">
                    <div class="count-details text-center text-md-start">
                        <h6 id="black-text">Students</h6>
                        <h3 id="black-text"><?= $studentCount ?: 0 ?></h3>
                    </div>
                    <div>
                        <i class="bi bi-person"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col p-2">
            <div id="course-container" class="count-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between w-100">
                    <div class="count-details text-center text-md-start">
                        <h6 id="black-text">Courses</h6>
                        <h3 id="black-text"><?= $courseCount ?: 0 ?></h3>
                    </div>
                    <div>
                        <i class="bi bi-mortarboard"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col p-2">
            <div id="subject-container" class="count-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between w-100">
                    <div class="count-details text-center text-md-start">
                        <h6 id="black-text">Subjects</h6>
                        <h3 id="black-text"><?= $subjectCount ?: 0 ?></h3>
                    </div>
                    <div>
                        <i class="bi bi-book"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col p-2">
            <div id="instructor-container" class="count-container d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between w-100">
                    <div class="count-details text-center text-md-start">
                        <h6 id="black-text">Instructors</h6>
                        <h3 id="black-text"><?= $instructorCount ?: 0 ?></h3>
                    </div>
                    <div>
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>