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
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);




$id = $_GET['id'];
$user = User::find($id);

if (!$user || !isset($user->id)) {
    header("Location: ../404.php");
    exit;
}

include '../layout/header.php';


if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') { //to see if talagang mag delete
    $grades = Grade::where('instructor_id', '=', $user->id);
         if ($grades) {
             foreach ($grades as $grade) {
                 $grade->delete();
             }
         }

      $subjects = Subject::where('instructor_id', '=', $user->id);
         if ($subjects) {
             foreach ($subjects as $subject) {
                 $subject->delete();
             }
         }
    
    $deleteUser = $user->delete();

    if ($deleteUser) {
        echo '<script>
                Swal.fire({
                    title: "Deleted!",
                    text: "The user has been deleted.",
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
                    text: "Failed to delete the user. Please try again.",
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
                title: "Delete this user?",
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
