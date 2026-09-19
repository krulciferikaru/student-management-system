<?php
require_once '../database/Database.php';
require_once '../models/Grade.php';
require_once '../models/Subject.php';
require_once '../models/Student.php';
require_once '../models/User.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Grade::setConnection($conn);
Subject::setConnection($conn);
User::setConnection($conn);
Student::setConnection($conn);

// $id = $_GET['id']; 

$subjects = Subject::find($_GET['id']);
$students = $subjects->students();
$user = User::findEmail($_SESSION['email']);

include '../layout/header.php';
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 55%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-start align-items-center">
                <a href="../reports/student_grades.php?id=<?= $subjects->id ?>" target="_blank" class="btn export-btn" style="width: 25%;">Download PDF</a>
                <a href="grades_sum.php?id=<?= $subjects->id ?>" class="btn ms-2" style="width: 25%;">View Summary</a>
                <a href="index.php" class="cancel-btn ms-auto">&times;</a>
            </div>
        </div>

        <h2 class="details-title text-center fw-bolder mb-3" id="text"> <?= $subjects->name ?> (<?= $subjects->catalog_no ?>)</h2>

        <div class="table-responsive">
            <table id="studentGradesTable" class="table table-borderless text-center mx-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1; ?>
                    <?php if (empty($students)): ?> <!-- if walang laman yung database !-->
                        <tr>
                            <td colspan="6" class="text-center">No students found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student) :
                            $enrollment = Enrollment::find($student->id);
                            if ($student->status == 'Inactive' || ($enrollment && $enrollment->status == 'Dropped')) {
                                continue; // Skip inactive or dropped students
                            }
                        ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $student->student_id ?></td>
                                <td><?= $student->name ?></td>
                                <td><?= $student->studentGrade($subjects->id)?->grade ?? 'No Grade Yet'; ?></td>
                                <td><?= $student->studentGrade($subjects->id)?->remarks ?? 'No Remarks Yet'; ?></td>
                                <td class="d-flex justify-content-center gap-2">
                                    <div class="actions-dropdown">
                                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <?php if ($student->studentGrade($subjects->id) !== null): ?>
                                                <li>
                                                    <a href="edit.php?id=<?= $student->studentGrade($subjects->id)->id ?>&student_id=<?= $student->id ?>&subject_id=<?= $subjects->id ?>&instructor_id=<?= $user->id ?>" class="edit-btn">
                                                        <i class="bi bi-pencil-square"></i>Edit
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if ($student->studentGrade($subjects->id) == null): ?>
                                                <li><a href="create.php?student_id=<?= $student->id ?>&subject_id=<?= $subjects->id ?>&instructor_id=<? $user->id ?>" class="add-btn"><i class="bi bi-plus-circle"></i>Add</a></li>
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
</div>

<?php include '../layout/footer.php'; ?>