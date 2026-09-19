<?php
require_once '../database/Database.php';
require_once '../models/Course.php';
require_once '../models/User.php';

session_start(); //start session para magamit yung session variable

if (!isset($_SESSION['email'])) {
    header('Location: auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Course::setConnection($conn);
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);

$id = $_GET['id'];
$course = Course::find($id); 

if (!$course) {
    header("Location: ../404.php");
    exit;
}

include '../layout/header.php';

$exists = Course::where('code', '=', $_POST['code']);
if ($exists) {
    foreach ($exists as $existingCourse) {
        if ($existingCourse->id != $id) {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Course code already taken!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'edit.php?id=" . $course->id . "';
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
    "name" => $_POST["name"]
];

$updateCourse = $course->update($data);

if ($updateCourse) {
    echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Course has been updated.',
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
                        text: 'Failed to updated course, try again.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'create.php';
                    });
                </script>";
}


?>

<?php include '../layout/footer.php'; ?>