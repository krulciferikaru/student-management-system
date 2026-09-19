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
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);


$id = $_GET['id'];
$course = Course::find($id); 

if (!$course) {
    header("Location: ../404.php");
    exit;
}

include '../layout/header.php';
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative">
    <div class="glass-container d-flex flex-column mx-auto position-relative w-100" style="max-width: 55%;">
        <!-- Cancel Button -->
        <a href="index.php" class="cancel-btn">&times;</a> <!-- unicode for x button -->
        <!-- Header -->
        <h1 class="text-center" id="black-text">Edit Course</h1>
        <form action="update.php?id=<?= $course->id ?>" method="POST">

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="code" id="black-text">Course Code</label>
                    <input type="text" class="form-control" id="code" name="code" value="<?= $course->code ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="name" id="black-text">Course Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $course->name ?>" required>
                </div>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn" style="width: 25%;">Edit</button>
            </div>
    </div>
    </form>
</div>
</div>
</div>

<?php


include '../layout/footer.php'; ?>