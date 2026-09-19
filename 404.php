<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: auth/login.php');
}
require_once 'layout/header.php';

?>

<link rel="stylesheet" href="css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative">
    <div>
        <h2 class="details-title text-center mb-3" id="black-text">404 Page Not Found</h2>
        <p class="text-center">The page you are looking for does not exist. Please go back to the <a href="index.php">homepage</a>.</p>
    </div>
</div>

<?php include 'layout/footer.php'; ?>