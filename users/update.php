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

if (empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id']; // to make sure the id is an integer
$user = User::find($id);

include '../layout/header.php';
?>

<?php
if (!$user) {
    echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'User not found.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        "name" => $_POST["name"],
        "email" => $_POST["email"],
        "role" => $_POST["role"],
        "status" => $_POST["status"]
    ];

    $updateUser = $user->update($data);

    if ($updateUser) {
        echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'User has been updated.',
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
                        text: 'Failed to update user. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<?php include '../layout/footer.php'; ?>