<?php
require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/Grade.php';
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}
include '../layout/header.php';

$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);

User::setConnection($conn);
Grade::setConnection($conn);

$data = [];

$data = [
    "student_id" => $_POST["student_id"],
    "subject_id" => $_POST["subject_id"],
    "instructor_id" => $_POST["instructor_id"],
    "grade" => $_POST["grade"],
    "remarks" => $_POST["remarks"]
];

$createGrade = Grade::create($data);

if ($createGrade) {
    echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Subject has been updated.',
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
                    text: 'Failed to updated subject, try again.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'create.php';
                });
            </script>";
}


?>

<?php include '../layout/footer.php'; ?>