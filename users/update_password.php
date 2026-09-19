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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // sa pagupdate ng password
    if (isset($_GET['action']) && $_GET['action'] === 'update_password') {
        $currentPassword = $_POST['password'];  // Current password (nasa database)
        $newPassword = $_POST['new_password'];  // New password (eto yung iuupdate)

        // Check nito if yung current password ay nagmmatch doon sa password sa database
        if (!password_verify($currentPassword, $user->password)) {
            echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Current password is incorrect.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        }).then(function() {
                            window.location = 'update_password.php?id=$user->id';
                        });
                    </script>";
            exit;
        }

        // Check if yung current password and new password ay same
        if ($currentPassword === $newPassword) {
            echo "<script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'New password cannot be the same as the current password.',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        }).then(function() {
                            window.location = 'update_password.php?id=$user->id';
                        });
                    </script>";
            exit;
        }

        // update the password after lahat ng checks
        $user->password = $newPassword;
        $user->save(); //i-hhash na yung password dito sa save method

        echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'User password has been updated.',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then(function() {
                        window.location = 'index.php';
                    });
                </script>";
    }
}
?>

<link rel="stylesheet" href="../css/style.css">
<div class="container vh-100 d-flex align-items-center justify-content-center position-relative">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 55%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-start align-items-center">
                <a href="index.php" class="cancel-btn ms-auto">&times;</a>
            </div>
        </div>

        <h2 class="text-center fw-bolder" id="text">Update Password</h2>

        <form action="update_password.php?id=<?= $user->id ?>&action=update_password" method="POST">
            <input type="hidden" name="id" value="<?= $user->id ?>">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="confirm_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn" style="width: 25%;">Update</button>
            </div>
        </form>
    </div>
</div>

<?php include '../layout/footer.php'; ?>