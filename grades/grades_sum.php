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
include '../layout/header.php';

$db = new Database();
$conn = $db->getConnection();
Grade::setConnection($conn);
Subject::setConnection($conn);
User::setConnection($conn);
Student::setConnection($conn);

$id = $_GET['id'];
$subject = Subject::find(id: $id);
$students = $subject->students();

?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative">
    <div class="glass-container d-flex flex-column mx-auto position-relative w-100" style="max-width: 55%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-end align-items-center">
                <a class="btn export-btn" href="../reports/reports_summary.php?id=<?= $subject->id ?>" target="_blank">Download PDF</a>
                <a href="../grades/view.php?id=<?= $subject->id ?>" class="cancel-btn">&times;</a> <!-- unicode for x button -->
            </div>
        </div>
        <h2 class="details-title text-center" id="black-text">Grades Summary</h2>

        <h5 id="black-text" class="text-center"><?= $subject->catalog_no ?>: <?= $subject->name ?></h5>
        
        <div class="justify-content-center justify-content-md-start align-items-center gap-3">
        <p><strong id="black-text">Number of Students:</strong> <?= count($students) ?></p>
        <p><strong>Passed:</strong> <?= Grade::remarks($subject->id, 'Passed') ?></p>
        <p><strong>Failed:</strong> <?= Grade::remarks($subject->id, 'Failed') ?></p>
        <p><strong>Pending:</strong> <?= Grade::remarks($subject->id, 'Pending') ?></p>
        <p><strong>Incomplete:</strong> <?= Grade::remarks($subject->id, 'Incomplete') ?></p>
        </div>

    </div>
</div>
</div>

<?php include '../layout/footer.php'; ?>