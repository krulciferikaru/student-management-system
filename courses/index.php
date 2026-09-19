<?php
require_once '../database/Database.php';
require_once '../models/Course.php';
require_once '../models/User.php';
session_start(); //start session para magamit yung session variable

if (!isset($_SESSION['email'])) {
    header('Location: auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Course::setConnection($conn);
Student::setConnection($conn);
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);
$courses = Course::all();

include '../layout/header.php';




// Debugging line to check the contents of $courses
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: auto;">
        <h1 class="text-center" id="black-text">Courses</h1>

        <div class="row gy-2 align-items-center mb-3">
            <div class="col-12 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start gap-2">
                <a class="btn w-10 w-md-auto" href="create.php">Add Course</a>
                <div class="export-as-dropdown-btn"></div>
            </div>
        </div>

        <div class="table-responsive">

            <table id="coursesTable" class="text-center table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Course ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="12" class="text-center">No courses found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $course->code ?></td>
                                <td><?= $course->name ?></td>
                                <td class="align-middle text-center">
                                    <div class="actions-dropdown d-flex justify-content-center align-items-center">
                                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a href="view.php?id=<?= $course->id ?>" class="view-btn"><i class="bi bi-eye"></i>View</a></li>
                                            <li><a href="edit.php?id=<?= $course->id ?>" class="edit-btn"><i class="bi bi-pencil-square"></i>Edit</a></li>
                                            <li><a href="destroy.php?id=<?= $course->id ?>" class="delete-btn"><i class="bi bi-trash"></i>Delete</a></li>
                                        </ul>
                                    </div>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>


<?php include '../layout/footer.php'; ?>