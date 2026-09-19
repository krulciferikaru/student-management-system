<?php
require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/Course.php';
require_once '../models/User.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);
Course::setConnection($conn);
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);

$student = Student::find($_GET['id']);
$courses = Course::all();

if (!$student) {
    header('Location: ../404.php');
    exit;
}

include '../layout/header.php';
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
        <h2 class="text-center" id="black-text">Edit Student</h2>
        <form id="edit-form" action="update.php" method="POST">
            <input type="hidden" name="id" value="<?= $student->id ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label id="black-text">Student ID (cannot be changed)</label>
                    <input type="text" class="form-control" value="<?= $student->student_id ?>" disabled>
                </div>
                <div class="col-md-6">
                    <label id="black-text">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= $student->name ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label id="black-text">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option value="Male" <?= $student->gender === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $student->gender === 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label id="black-text">Date of Birth</label>
                    <input type="date" name="birthdate" class="form-control" value="<?= $student->birthdate ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label id="black-text">Course</label>
                    <select name="course_id" class="form-select" required>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course->id ?>" <?= $student->course_id == $course->id ? 'selected' : '' ?>>
                                <?= $course->code ?> - <?= $course->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label id="black-text">Year Level</label>
                    <select name="year_level" class="form-select" required>
                        <?php foreach (['1', '2', '3', '4'] as $year): ?>
                            <option value="<?= $year ?>" <?= $student->year_level == $year ? 'selected' : '' ?>>
                                <?= $year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="button" id="update-btn" class="btn btn-success" style="width: 25%;">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('update-btn').addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update the student information?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('edit-form');
                const formData = new FormData(form);

                fetch('update.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (response.redirected) {
                            const params = new URL(response.url).searchParams;
                            const id = params.get("id");

                            Swal.fire({
                                title: 'Updated!',
                                text: 'Student information updated successfully.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'view.php?id=' + id;
                            });
                        } else {
                            Swal.fire('Error', 'Update failed. Please try again.', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Something went wrong during update.', 'error');
                    });
            }
        });
    });
</script>

<?php include '../layout/footer.php'; ?>