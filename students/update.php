<?php 
require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/User.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);

$id = $_POST['id'];
$student = Student::find($id);

if (!$student) {
    header("Location: view.php?id=$id&error=notfound");
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        "name" => $_POST["name"],
        "gender" => $_POST["gender"],
        "birthdate" => $_POST["birthdate"],
        "course_id" => $_POST["course_id"],
        "year_level" => $_POST["year_level"]
    ];

    $updated = $student->update($data);

    if ($updated) {
        header("Location: edit.php?id=$id&success=updated");
    } else {
        header("Location: edit.php?id=$id&error=update_failed");
    }
    exit;
}


