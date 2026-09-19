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

$users = User::all();

if (isset($_GET['role']) && $_GET['role'] != '') {
    $filteredUsers = [];
    foreach ($users as $user) {
        if ($user->role === $_GET['role']) {
            $filteredUsers[] = $user;
        }
    }
    $users = $filteredUsers;
}
include '../layout/header.php';

?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative">

        <h1 class="text-center" id="black-text">Users</h1>

        <div class="row gy-2 align-items-center mb-3">
            <div class="col-12 col-md-9 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start gap-2">
                <a class="btn" href="create.php">Add User</a>
                <div class="export-as-dropdown-btn"></div>
            </div>

            <?php if ($_SESSION['role'] === 'Super-admin'): ?>
                <div class="col-12 col-md-3">
                    <form action="" method="GET">
                        <select class="form-select w-100" id="role" name="role" onchange="this.form.submit()">
                            <option value="" <?php if (isset($_GET['role']) && $_GET['role'] === '') echo 'selected'; ?> disabled selected hidden>Filter by Role</option>
                            <option value="" <?php if (isset($_GET['role']) && $_GET['role'] === '') echo 'selected'; ?>>All</option>
                            <option value="Instructor" <?php if (isset($_GET['role']) && $_GET['role'] === 'Instructor') echo 'selected'; ?>>Instructor</option>
                            <option value="Admin" <?php if (isset($_GET['role']) && $_GET['role'] === 'Admin') echo 'selected'; ?>>Admin</option>
                        </select>
                    </form>
                </div>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table id="usersTable" class="table table-borderless text-center mx-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="12" class="text-center" id="text">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <?php if (($_SESSION['role'] === 'Super-admin' && in_array($user->role, ['Instructor', 'Admin'])) || ($_SESSION['role'] === 'Admin' && $user->role === 'Instructor')): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= $user->name ?></td>
                                    <td><?= $user->email ?></td>
                                    <td><?= $user->role ?></td>
                                    <td>
                                         <?php if ($user->status === 'Active'): ?>
                                                <div class='btn' style="background-color:rgb(66, 164, 104)">Active</div>
                                            <?php else: ?>
                                                <div class= 'btn' style="background-color:rgb(202, 89, 89)">Inactive</div></li>
                                    <?php endif; ?>
                                    </td>
                                    <td class="d-flex justify-content-center">
                                        <div class="actions-dropdown">
                                            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                            <ul class="dropdown-menu">
                                                <li><a href="view.php?id=<?= $user->id ?>" class="view-btn"><i class="bi bi-eye"></i>View</a></li>
                                                <li><a href="edit.php?id=<?= $user->id ?>" class="edit-btn"><i class="bi bi-pencil-square"></i>Edit</a></li>
                                                <?php if ($_SESSION['role'] === 'Super-admin' || ($_SESSION['role'] === 'Admin' && $user->role === 'Instructor')): ?>
                                                    <?php if ($user->status === 'Active'): ?>
                                                        <li><a href="status.php?id=<?= $user->id ?>" class="deactivate-btn"><i class="bi bi-toggle-off"></i>Disable</a></li>
                                                    <?php else: ?>
                                                        <li><a href="status.php?id=<?= $user->id ?>" class="activate-btn"><i class="bi bi-toggle-on"></i>Enable</a></li>
                                                    <?php endif; ?>
                                                    <li><a href="destroy.php?id=<?= $user->id ?>"><i class="fa-regular fa-trash-can"></i>Delete</a></li>
                                                <?php endif; ?>                                            
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layout/footer.php'; ?>