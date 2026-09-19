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

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 55%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-start align-items-center">
                <a href="index.php" class="cancel-btn ms-auto">&times;</a>
            </div>
        </div>
        <h2 class="text-center" id="black-text">Update User</h2>

        <form action="update.php?id=<?= $user->id ?>" method="POST">
            <!-- NAME -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="code" id="black-text">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $user->name ?>" required>
                </div>
            </div>
            <!-- EMAIL -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="name" id="black-text">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $user->email ?>" required>
                </div>
            </div>
            <!-- ROLE -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" id="black-text">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <?php if ($_SESSION['role'] === 'Super-admin'): ?>
                            <option value="Instructor" <?= $user->role == 'Instructor' ? 'selected' : '' ?>>Instructor</option>
                            <option value="Admin" <?= $user->role == 'Admin' ? 'selected' : '' ?>>Admin</option>
                        <?php else: ?>
                            <option value="Instructor" <?= $user->role == 'Instructor' ? 'selected' : '' ?>>Instructor</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="name" id="black-text">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Active" <?= $user->status == 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= $user->status == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="row mb-3">
                <?php if ($_SESSION['role'] === 'Super-admin'): ?>
                    <div class="col-md-6 mb-3">
                        <a href="reset_password.php?id=<?= $user->id ?>&action=reset_password" class="btn reset-password-btn">Reset Password</a>
                    </div>
                <?php endif; ?>

                <div class="col-md-6">
                    <a href="update_password.php?id=<?= $user->id ?>&action=update_password" class="btn update-password-btn">Update Password</a>
                </div>
            </div>

            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn" style="width: 25%;">Save</button>
            </div>
        </form>
    </div>
</div>

<?php


include '../layout/footer.php'; ?>