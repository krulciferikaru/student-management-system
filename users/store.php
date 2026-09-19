<?php
require_once '../database/Database.php';
require_once '../models/User.php';

session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);

if (!isset($_POST["name"], $_POST["email"], $_POST["password"], $_POST["role"], $_POST["status"])) {
    header('Location: ../404.php');
    exit;
}

require_once '../layout/header.php';
?>

<?php

$ifEmailExists = User::findEmail($_POST["email"]);
if ($ifEmailExists) {
    echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Email already exists.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'create.php';
                });
            </script>";
    exit;
}

if ($_POST['password'] !== $_POST['confirm_password']) {
    echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Password does not match.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'create.php';
                });
            </script>";
    exit;
}

$data = [
    "name" => $_POST["name"],
    "email" => $_POST["email"],
    "password" => $_POST["password"],
    "role" => $_POST["role"],
    "status" => $_POST["status"]
];

$createUser = User::create($data);

if ($createUser) {
    echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'User has been added.',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(function() {
                    window.location = 'index.php';
                });
            </script>";
} else {
    echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to add user.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            </script>";
}

include '../layout/footer.php';
?>