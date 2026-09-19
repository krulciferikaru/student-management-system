<?php
session_start();

require_once 'database/Database.php';
require_once 'models/User.php';

$db = new Database();
$conn = $db->getConnection();
User::setConnection($conn);

if (!isset($_SESSION['email'])) {
    header('Location: auth/login.php');
    exit;
}

$role = $_SESSION['role'];

if ($role == 'Instructor') {
  header('Location: dashboard/instructor-dashboard.php');
} elseif ($role == 'Admin') {
  header('Location: dashboard/admin-dashboard.php');
} elseif ($role == 'Super-admin') {
  header('Location: dashboard/super-admin-dashboard.php');
}

?>