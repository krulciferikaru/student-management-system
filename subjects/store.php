<?php
require_once '../database/Database.php';
require_once '../models/Subject.php';
require_once '../models/User.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}


$db = new Database();
$conn = $db->getConnection();
Subject::setConnection($conn);
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);

if (!isset($_POST["code"], $_POST["catalog_no"], $_POST["name"], $_POST["day"], $_POST["time"], $_POST["room"], $_POST["course_id"], $_POST["semester"], $_POST["year_level"], $_POST["instructor_id"])) {
    header('Location: ../404.php');
    exit;
}

include '../layout/header.php';

$exists = Subject::where('code', '=', $_POST['code']);

if ($exists) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Subject code already taken!',
            icon: 'warning',
            confirmButtonText: 'Ok'
        }).then(function() {
            window.location = 'create.php';
        });
    </script>";
    exit;
}

$subjectsInRoom = Subject::where('room', '=', $_POST['room']);

if ($subjectsInRoom) {
    foreach ($subjectsInRoom as $subject) {
        
        if ($subject->day === $_POST['day'] && $subject->time === $_POST['time']) {
            echo "<script>
                Swal.fire({
                    title: 'Schedule Conflict!',
                    text: 'Room " . $_POST['room'] . " is already occupied on " . $_POST['day'] . " at " . $_POST['time'] . "',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'create.php';
                });
            </script>";
            include '../layout/footer.php';
            exit;
        }
    }
}


$instructorSubjects = Subject::where('instructor_id', '=', $_POST['instructor_id']);

if ($instructorSubjects) {
    foreach ($instructorSubjects as $subject) {
        if ($subject->day === $_POST['day'] && $subject->time === $_POST['time']) {
            echo "<script>
                Swal.fire({
                    title: 'Schedule Conflict!',
                    text: 'The instructor already has a class on " . $_POST['day'] . " at " . $_POST['time'] . "',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'create.php';
                });
            </script>";
            include '../layout/footer.php';
            exit;
        }
    }
}

$data = [];

$data = [
    "code" => $_POST["code"],
    "catalog_no" => $_POST["catalog_no"],
    "name" => $_POST["name"],
    "day" => $_POST["day"],
    "time" => $_POST["time"],
    "room" => $_POST["room"],
    "course_id" => $_POST["course_id"],
    "semester" => $_POST["semester"],
    "year_level" => $_POST["year_level"],
    "instructor_id" => $_POST["instructor_id"]
];

$createCourse = Subject::create($data);

if ($createCourse) {
    echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Subject has been added.',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
} else {
    echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to add subject, try again.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'create.php';
                    });
                </script>";
}


?>

<?php include '../layout/footer.php'; ?>