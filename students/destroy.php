<?php
require_once '../database/Database.php';
require_once '../models/Course.php';
require_once '../models/Student.php';
require_once '../models/Subject.php';
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
Grade::setConnection($conn);



$id = $_GET['id'];
$student = Student::find($id);

if (!$student || !isset($student->id)) {
    header("Location: ../404.php");
    exit;
}

include '../layout/header.php';


if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') { //to see if talagang mag delete
    $grades = Grade::where('student_id', '=', $student->id);
        if ($grades) {
            foreach ($grades as $grade) {
                $grade->delete();
            }
        }
    
    $enrollments = Enrollment::where('student_id', '=', $student->id);
        if ($enrollments) {
            foreach ($enrollments as $enrollment) {
                $enrollment->delete();
            }
        }
    
    $deleteStudent = $student->delete();

    if ($deleteStudent) {
        echo '<script>
                Swal.fire({
                    title: "Deleted!",
                    text: "The student record has been deleted.",
                    icon: "success",
                    confirmButtonText: "Ok"
                }).then(function() {
                    window.location.href = "index.php";
                });
              </script>';
    } else {
        echo '<script>
                Swal.fire({
                    title: "Error!",
                    text: "Failed to delete the student record. Please try again.",
                    icon: "error",
                    confirmButtonText: "Ok"
                }).then(function() {
                    window.location.href = "index.php";
                });
              </script>';
    }
} else {
    echo '<script>
            Swal.fire({
                title: "Delete this student record?",
                text: "This action cannot be undone! You can deactivate instead.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "destroy.php?id=' . $id . '&confirm=yes";
                } else {
                    window.location.href = "index.php";
                }
            });
          </script>';
}

include '../layout/footer.php';
