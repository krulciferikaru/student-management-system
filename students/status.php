<?php
require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/User.php';

session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);

$id = $_GET['id'] ?? null;

// $action = $_GET['action'] ?? null;

// if (!$id || !$action) {
//     header("Location: index.php");
//     exit;
// }

$student = Student::find($id);

// if (!$student) {
//     header("Location: index.php");
//     exit;
// }

// if ($action === 'activate' && $student->status === 'Inactive') {
//     $update = $student->update(['status' => 'Active']);
//     $statusText = 'activated';
// } elseif ($action === 'deactivate' && $student->status === 'Active') {
//     $update = $student->update(['status' => 'Inactive']);
//     $statusText = 'deactivated';
// } else {
//     header("Location: index.php");
//     exit;
// }

// if ($update) {
//     header("Location: index.php");
//     exit;
// } else {
//     header("Location: index.php?error=update_failed");
//     exit;
// }

// 
if (!$student || !isset($student->id)) {
    header("Location: ../404.php");
    exit;
}
require_once '../layout/header.php';
?>

<?php
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    $status = $student->changeStatus();

    if (!$status) {
        die("Error: " . $conn->errorInfo());
    }

    if ($status) {
        $changeStatus = ($student->status === 'Active') ? 'deactivated' : 'activated';
        echo '<script>
                    Swal.fire({
                        title: "Success!",
                        text: "The user has been ' . $changeStatus  . '.",
                        icon: "success",
                        confirmButtonText: "Ok"
                    }).then(function() {
                        window.location.href = "index.php";
                    });
                </script>';
    } else {
        $changeStatus = ($student->status === 'Active') ? 'deactivate' : 'activate';
        echo '<script>
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to ' . $changeStatus . ' the user. Please try again.",
                        icon: "error",
                        confirmButtonText: "Ok"
                    }).then(function() {
                        window.location.href = "index.php";
                    });
                </script>';
    }
} else {
    $action = ($student->status === 'Active') ? 'deactivate' : 'activate';
    $changeStatus = ($student->status === 'Active') ? 'inactive' : 'active';

    echo '<script>
                Swal.fire({
                    title: "Do you want to ' . $action . ' this user?",
                    text: "This will set the user\'s status to ' . $changeStatus . '.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, ' . $action . ' it!",
                    cancelButtonText: "No, cancel!",
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "status.php?id=' . $id . '&confirm=yes";
                    } else {
                        window.location.href = "index.php";
                    }
                });
            </script>';
}
?>