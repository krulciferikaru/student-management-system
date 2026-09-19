<?php
require_once '../database/Database.php';
require_once '../models/User.php';
require_once '../models/Subject.php';
require_once '../models/Course.php';

session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);
Course::setConnection($conn);

$id = $_GET['id'];
$user = User::find($id);

if (!$user || !isset($user->id)) {
    header("Location: ../404.php");
    exit;
}

$subjects = $user->subjects();

require_once '../layout/header.php';
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 55%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-start align-items-center">
                <?php if ($user->role == 'Instructor'): ?>
                    <a href="../reports/instructor_profile.php?id=<?= $user->id ?>" class="btn export-btn" target="_blank" style="width: 25%;">Download PDF</a>
                <?php endif; ?>

                <a href="index.php" class="cancel-btn ms-auto">&times;</a>
            </div>
        </div>

        <div class="row mb-3">
            <h2 class="details-title text-center fw-bolder mb-3" id="text"><?php echo $user->role ?> Details</h2>

            <div class="d-flex flex-wrap justify-content-center  justify-content-md-start align-items-center gap-3">
                <img class="pfp-picture" src="../css/images/Profile Picture.png"></img>

                <div class="user-details d-flex flex-column justify-content-center align-items-center align-items-md-start">
                    <label for="name" id="black-text">Full Name</label>
                    <p><?= $user->name ?></p>
                    <label for="email" id="black-text">Email Address</label>
                    <p><?= $user->email ?></p>
                    <label for="role" id="black-text">Role</label>
                    <p><?= $user->role ?></p>
                    <label for="status" id="black-text">Status</label>
                    <p><?= $user->status ?></p>
                </div>
            </div>
        </div>
        <?php if ($user->role == 'Instructor'): ?>
            <div class="row mb-3">
                <h2 class="details-title text-center fw-bolder mt-3 mb-3" id="text">List of Assigned Subjects</h2>
                <?php if (empty($subjects)): ?>
                    <p colspan="4" class="text-center">No subjects found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table id="subjectsTableDetails" class="table table-borderless text-center">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Course</th>
                                    <th>Year Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjects as $subject): ?>
                                    <tr>
                                        <td><?= $subject->code ?></td>
                                        <td><?= $subject->name ?></td>
                                        <td><?= Course::find($subject->course_id)->name ?></td>
                                        <td><?= $subject->year_level ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include '../layout/footer.php'; ?>