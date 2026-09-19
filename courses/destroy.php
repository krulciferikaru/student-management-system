<?php  

     require_once '../database/Database.php';
     require_once '../models/Course.php';
     require_once '../models/Student.php';
     require_once '../models/User.php';
    session_start(); //start session para magamit yung session variable

    if(!isset($_SESSION['email'])){
        header('Location: auth/login.php');
    } 
    
   
     
     $db = new Database();
     $conn = $db->getConnection();
     Course::setConnection($conn);
     User::setConnection($conn);
     User::requireRole(['Admin', 'Super-admin']);


    $id = $_GET['id'];
    $course = Course::find($id); 
    if (!$course){
        header("Location: ../404.php");
        exit;
    }
    
    include '../layout/header.php'; 
        
    
if ($course->student() > 0){ //to check if may enrolled students sa course na to
    echo '<script>
            Swal.fire({
                title: "Error!",
                text: "Cannot delete this course. There are enrolled students.",
                icon: "error",
                confirmButtonText: "Ok"
            }).then(function() {
                window.location.href = "index.php";
            });
          </script>';
    exit;
}

if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') { //to see if talagang mag delete
    $deleteCourse = $course->delete();

    if (!$deleteCourse) {
        die("Error: " . $conn->errorInfo());
    }

    if ($deleteCourse) {
        echo '<script>
                Swal.fire({
                    title: "Deleted!",
                    text: "The course record has been deleted.",
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
                    text: "Failed to delete the course record. Please try again.",
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
                title: "Delete this course record?",
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

include '../layout/footer.php'; ?>