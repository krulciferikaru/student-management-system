<?php
require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/User.php';

session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);

if (!isset($_POST["student_id"], $_POST["name"], $_POST["gender"], $_POST["dob"], $_POST["course_id"], $_POST["year_level"])) {
    header('Location: ../404.php');
    exit;
}

include '../layout/header.php';

$exists = Student::where('student_id', '=', $_POST['student_id']);

if ($exists) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Student number already taken!',
            icon: 'warning',
            confirmButtonText: 'Ok'
        }).then(function() {
            window.location = 'create.php';
        });
    </script>";
    exit;
}


$data = [
    "student_id" => $_POST["student_id"],
    "name" => $_POST["name"],
    "gender" => $_POST["gender"],
    "birthdate" => $_POST["dob"],
    "course_id" => $_POST["course_id"],
    "year_level" => $_POST["year_level"],
    "status" => "Active",
];

try {
    $createStudent = Student::create($data);
    if ($createStudent) {
        header("Location: create.php?status=success");
    } else {
        header("Location: create.php?status=fail");
    }
} catch (Exception $e) {
    header("Location: create.php?status=error&msg=" . urlencode($e->getMessage()));
}
exit;
