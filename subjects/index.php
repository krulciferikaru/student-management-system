<?php
require_once '../database/Database.php';
require_once '../models/Course.php';
require_once '../models/User.php';
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Subject::setConnection($conn);
Course::setConnection($conn);
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);

$subjects = Subject::all();

include '../layout/header.php';

?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 100%">
        <h1 class="text-center fw-bolder mb-4" id="text">Subjects</h1>

        <div class="row gy-2 align-items-center mb-3">
            <div class="col-12 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start gap-2">
                <a class="btn w-10 w-md-auto" href="create.php">Add Subject</a>
                <div class="export-as-dropdown-btn"></div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="subjectsTable" class="text-center table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Catalog No.</th>
                        <th>Name</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Room</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Semester</th>
                        <th>Instructor</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php if (empty($subjects)): ?>
                        <tr>
                            <td colspan="12" class="text-center" id="text">No subjects found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($subjects as $subject): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $subject->code ?></td>
                                <td><?= $subject->catalog_no ?></td>
                                <td><?= $subject->name ?></td>
                                <td><?= $subject->day ?></td>
                                <td><?= $subject->time ?></td>
                                <td><?= $subject->room ?></td>
                                <td><?= Course::find($subject->course_id)->name ?></td>
                                <td><?= $subject->year_level ?></td>
                                <td><?= $subject->semester ?></td>
                                <td><?= User::find($subject->instructor_id)->name ?></td>
                                <td class="align-middle text-center">
                                    <div class="actions-dropdown d-flex justify-content-center align-items-center">
                                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a href="view.php?id=<?= $subject->id ?>" class="view-btn"><i class="bi bi-eye"></i>View</a></li>
                                            <li><a href="edit.php?id=<?= $subject->id ?>" class="edit-btn"><i class="bi bi-pencil-square"></i>Edit</a></li>
                                            <?php if($_SESSION['role'] === 'Super-admin' || $_SESSION['role'] === 'Admin'): ?>
                                                <li><a href="destroy.php?id=<?= $subject->id ?>" class="delete-btn"><i class="bi bi-trash"></i>Delete</a></li>
                                            <?php endif; ?>                                    
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
    </div>
</div>

<?php include '../layout/footer.php'; ?>