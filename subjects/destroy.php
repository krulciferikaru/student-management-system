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
Subject::setConnection($conn);
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);

$id = $_GET['id'];
$subject = Subject::find($id);

if (!$subject || !isset($subject->id)) {
    header("Location: ../404.php");
    exit;
}

$students = $subject->students();

include '../layout/header.php';
if ($students) { //to check if may enrolled students sa subject na to
    echo '<script>
            Swal.fire({
                title: "Error!",
                text: "Cannot delete this subject. There are enrolled students.",
                icon: "error",
                confirmButtonText: "Ok"
            }).then(function() {
                window.location.href = "index.php";
            });
          </script>';
    exit;
}

if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') { //to see if talagang mag delete
    $deleteSubject = $subject->delete();

    if (!$deleteSubject) {
        die("Error: " . $conn->errorInfo());
    }

    if ($deleteSubject) {
        echo '<script>
                Swal.fire({
                    title: "Deleted!",
                    text: "The subject record has been deleted.",
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
                    text: "Failed to delete the subject record. Please try again.",
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
                title: "Delete this subject record?",
                text: "This action cannot be undone!",
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
