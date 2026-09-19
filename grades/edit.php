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
Student::setConnection($conn);
User::setConnection($conn);
$user = User::findEmail($_SESSION['email']);

$id = $_GET['id'] ?? null;

$grade = Grade::find($id);
$subject = Subject::find($_GET['subject_id']);
$instructor = User::find($subject->instructor_id);
$student = Student::find($_GET['student_id']);

if (!$grade) {
    echo "Grade not found.";
}

if (!$student) {
    echo "Student not found.";
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

        <h2 class="text-center" id="black-text"> Edit Student Grade</h2>
        <form action="update.php?id=<?= $grade->id ?>" method="POST">
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
                        <?php
                          $currentAve = $grade->grade;
                          $ii = [1.00, 1.25, 1.50, 1.75, 2.00, 2.25, 2.50, 2.75, 3.00, 5.00];
                        
                            foreach($ii as $i)
                            {
                                if ($i == $currentAve || $ii ==$currentAve)
                                {
                                    echo "<option selected>" . number_format($i,2) . "</option>"; 
                                }
                                else
                                {
                                    echo "<option>" .  number_format($i,2) ." </option>";
                                }
                            }
                         ?>
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label id="black-text" for="remarks">Remark</label>
                    <select class="form-select" id="remarks" name="remarks" required>
                        <option value="Passed" <?= $grade->remarks == 'Passed' ? 'selected' : '' ?>>Passed</option>
                        <option value="Failed" <?= $grade->remarks == 'Failed' ? 'selected' : '' ?>>Failed</option>
                        <option value="Pending" <?= $grade->remarks == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="INC" <?= $grade->remarks == 'INC' ? 'selected' : '' ?>>Incomplete</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn" style="width: 25%;">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
>>>>>>> Stashed changes

<?php include '../layout/footer.php'; ?>