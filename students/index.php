<?php
require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/User.php';

session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}
$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);
User::setConnection($conn);
$students = Student::all();

User::requireRole(['Admin', 'Super-admin']);
include '../layout/header.php';

?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 100%;">
        <h1 class="text-center fw-bolder mb-4" id="text">Students</h1>

        <div class="row gy-2 align-items-center mb-3">
            <div class="col-12 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start gap-2">
                <a class="btn w-10 w-md-auto" href="create.php">Add Student</a>
                <a class="export-as-dropdown-btn"></a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="studentsTable" class="text-center table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="12" class="text-center">No students found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= $student->student_id ?></td>
                                <td><?= $student->name ?></td>
                                <td><?= $student->gender ?></td>
                                <td><?= $student->course_id ?></td>
                                <td><?= $student->year_level ?></td>
                                <td>
                                    <?php if ($student->status === 'Active'): ?>
                                                <div class='btn' style="background-color:rgb(66, 164, 104)">Active</div>
                                            <?php else: ?>
                                                <div class= 'btn' style="background-color:rgb(202, 89, 89)">Inactive</div></li>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="actions-dropdown d-flex justify-content-center align-items-center">
                                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a href="view.php?id=<?= $student->id ?>" class="view-btn"><i class="bi bi-eye"></i>View</a></li>
                                            <li><a href="edit.php?id=<?= $student->id ?>" class="edit-btn"><i class="bi bi-pencil-square"></i>Edit</a></li>
                                            <?php if ($student->status === 'Active'): ?>
                                                <li><a href="status.php?id=<?= $student->id ?>" class="deactivate-btn"><i class="bi bi-toggle-off"></i>Disable</a></li>
                                            <?php else: ?>
                                                <li><a href="status.php?id=<?= $student->id ?>" class="activate-btn"><i class="bi bi-toggle-on"></i>Enable</a></li>
                                            <?php endif; ?>
                                            <li><a href="destroy.php?id=<?= $student->id ?>"><i class="fa-regular fa-trash-can"></i>Delete</a></li>
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
</div>

<?php include '../layout/footer.php'; ?>