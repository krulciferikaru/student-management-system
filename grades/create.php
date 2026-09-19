<?php
require_once '../database/Database.php';
require_once '../models/Grade.php';
require_once '../models/Student.php';
require_once '../models/Subject.php';
require_once '../models/User.php';

session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Grade::setConnection($conn);
User::setConnection($conn);
Student::setConnection($conn);
Subject::setConnection($conn);

$id = $_GET['id'] ?? null;

$student = Student::find($_GET['student_id']);
$subject = Subject::find($_GET['subject_id']);
$instructor = User::find($subject->instructor_id);

if (!$student || !$subject || !$instructor) {
    echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Student or Subject not found.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>";
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
                <a href="../grades/view.php?id=<?= $subject->id ?>" class="cancel-btn ms-auto">&times;</a>
            </div>
        </div>

        <h2 class="details-title text-center fw-bolder mb-3" id="text">Add Student Grade</h2>

        <form action="store.php" method="POST">
            <input type="hidden" name="student_id" value="<?= $student->id ?>">
            <input type="hidden" name="subject_id" value="<?= $subject->id ?>">
            <input type="hidden" name="instructor_id" value="<?= $subject->instructor_id ?>">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label id="black-text" for="subject_name">Subject Name</label>
                    <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?= $subject->name ?> (<?= $subject->catalog_no ?>)" readonly disabled>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label id="black-text" for="student_id">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" value="<?= $student->student_id ?>" readonly disabled>
                </div>
                <div class="col-md-6">
                    <label id="black-text" for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $student->name ?>" readonly disabled>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label id="black-text" for="grade">Grade</label>
                    <select id="grade" name="grade" class="form-select" required>
                        <option value="" disabled selected hidden>Select Grade</option>
                        <?php
                        $ii = [1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00];

                        foreach ($ii as $i) {
                            echo "<option>" .  number_format($i, 2) . " </option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label id="black-text" for="remarks">Remark</label>
                    <select class="form-select" id="remarks" name="remarks" required>
                        <option value="" disabled selected hidden>Select Remark</option>
                        <option value="Passed">Passed</option>
                        <option value="Failed">Failed</option>
                        <option value="Pending">Pending</option>
                        <option value="Incomplete">Incomplete</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn" style="width: 25%;">Add</button>
            </div>
        </form>
    </div>
</div>

<?php include '../layout/footer.php'; ?>