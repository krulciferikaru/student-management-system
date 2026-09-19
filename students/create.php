<?php
require_once '../database/Database.php';
require_once '../models/Course.php';
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

$courses = Course::all();

User::requireRole(['Admin', 'Super-admin']);

include '../layout/header.php';
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
        <h2 class="text-center" id="black-text">Add Student</h2>

        <form action="store.php" method="POST" id="student-form">
            <!-- Student ID -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="student_id" id="black-text">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" required>
                </div>
                <!-- Full Name -->
                <div class="col-md-6">
                    <label for="name" id="black-text">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
            <!-- Gender -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="gender" id="black-text">Gender</label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="" selected disabled hidden>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <!-- Date of Birth -->
                <div class="col-md-6">
                    <label for="dob" id="black-text">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <!-- Course -->
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="course_id" id="black-text">Course</label>

                    <select name="course_id" class="form-select" id="course_id" required>
                        <option value="" hidden selected>Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?= $course->id ?>">
                                <?= $course->code ?> - <?= $course->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Year Level -->
                <div class="col-md-6">
                    <label for="year_level" id="black-text">Year Level</label>
                    <select class="form-select" id="year_level" name="year_level" required>
                        <option value="" selected disabled hidden>Select Year Level</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>
                </div>
            </div>
            <!-- Buttons -->
            <div class="row mb-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn" style="width: 25%;">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php include '../layout/footer.php'; ?>

<script>
    document.getElementById('student-form').addEventListener('submit', function(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to add this student?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, add',
            cancelButtonText: 'No, cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            } else {
                window.location = 'create.php';
            }
        });
    });
</script>

<?php if (isset($_GET['status'])): ?>
    <script>
        <?php if ($_GET['status'] === 'success'): ?>
            Swal.fire({
                title: 'Success!',
                text: 'Student has been added.',
                icon: 'success',
                confirmButtonText: 'Ok'
            }).then(function() {
                window.location = 'index.php';
            });

        <?php elseif ($_GET['status'] === 'duplicate'): ?>
            Swal.fire({
                title: 'Duplicate Student ID',
                text: 'A student with this ID already exists!',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        <?php elseif ($_GET['status'] === 'fail'): ?>
            Swal.fire({
                title: 'Failed!',
                text: 'Could not add the student. Please try again.',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        <?php endif; ?>
    </script>
<?php endif;
include '../layout/footer.php'; ?>