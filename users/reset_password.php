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
User::requireRole(['Super-admin']);

$id = $_GET['id'];
$user = User::find($id);

if (!$user) {
    header('Location: ../404.php');
}
require_once '../layout/header.php';
?>

<?php
// reset password confirmation
if (isset($_GET['action']) && $_GET['action'] === 'reset_password') {
    echo '<script>
        Swal.fire({
            title: "Reset this user password?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, reset it!",
            cancelButtonText: "No, cancel!",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "reset_password.php?id=' . $id . '&confirm=yes";
            } else {
                window.location.href = "edit.php?id=' . $id . '";
            }
        });
    </script>';
}

// reset password
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    if ($user) {
        $temporaryPassword = User::passwordRandomizer(8);
        $user->password = $temporaryPassword;
        $user->save();

        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Your new password is $temporaryPassword.',
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
                    text: 'User not found.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>";
        exit;
    }
}
?>

<?php include '../layout/footer.php'; ?>