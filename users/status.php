<?php
require_once '../database/Database.php';
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
require_once '../layout/header.php';
?>

<?php
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    $status = $user->changeStatus();

    if (!$status) {
        die("Error: " . $conn->errorInfo());
    }

    if ($status) {
        $changeStatus = ($user->status === 'Active') ? 'deactivated' : 'activated';
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
        $changeStatus = ($user->status === 'Active') ? 'deactivate' : 'activate';
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
    $action = ($user->status === 'Active') ? 'deactivate' : 'activate';
    $changeStatus = ($user->status === 'Active') ? 'inactive' : 'active';

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

<?php include '../layout/footer.php'; ?>