<?php
require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/Grade.php';
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);

$id = $_GET['id'];
$grade = Grade::find($id);


if (!$grade) {
    header("fail");
    exit;
}
include '../layout/header.php';

$data = [];

$data = [
    "student_id" => $_POST["student_id"],
    "subject_id" => $_POST["subject_id"],
    "instructor_id" => $_POST["instructor_id"],
    "grade" => $_POST["grade"],
    "remarks" => $_POST["remarks"]
];

$updateGrade = $grade->update($data);

if ($updateGrade) {
    echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Grade has been updated.',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = '../grades/view.php?id=" . $_POST["subject_id"] . "';
                });
            </script>";
} else {
    echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to updated grade, try again.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = '../grades/view.php?id=" . $_POST["subject_id"] . "';
                });
            </script>";
}


?>

<?php include '../layout/footer.php'; ?>