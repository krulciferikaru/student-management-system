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

require_once '../layout/header.php';
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative">
    <div class="glass-container d-flex flex-column mx-auto position-relative w-100" style="max-width: 55%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-start align-items-center">
                <a href="index.php" class="cancel-btn ms-auto">&times;</a>
            </div>
        </div>    
        <!-- Header -->
        <h1 class="text-center" id="black-text">Add User</h1>

        <form action="store.php" method="POST">
            <!-- NAME -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="name" id="black-text">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>

            <!-- EMAIL -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="email" id="black-text">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <!-- PASSWORD & CONFIRM PASSWORD -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" id="black-text">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="col-md-6">
                    <label for="confirm_password" id="black-text">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            <!-- ROLE -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="role" id="black-text">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Super-admin'): ?>
                            <option value="" selected disabled hidden>Select Role</option>
                            <option value="Instructor">Instructor</option>
                            <option value="Admin">Admin</option>
                        <?php else: ?>
                            <option value="Instructor">Instructor</option>
                        <?php endif; ?>
                    </select>
                </div>
                <!-- STATUS -->
                <div class="col-md-6">
                    <label for="status" id="black-text">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="" selected disabled hidden>Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <!-- BUTTONS -->
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn" style="width: 25%;">Add</button>
            </div>
    </div>
    </form>
</div>
</div>

</html>

<?php include '../layout/footer.php'; ?>