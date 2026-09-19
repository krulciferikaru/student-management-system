<?php
ob_start(); // Start output buffering
require '../plugins/fpdf/fpdf.php';
require_once '../database/Database.php';
require_once '../Models/User.php';
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
require_once '../layout/header.php';


$id = $_GET['id'] ?? null;
$user = User::find($id);
$subjects = $user->subjects();

if (!$user) {
    die("User not found.");
}

$pdf = new FPDF();
$pdf->AddPage();
// Profile
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Instructor Profile', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(40, 10, 'Full Name:', 0);
$pdf->Cell(0, 10, $user->name, 0, 1);

$pdf->Cell(40, 10, 'Email:', 0);
$pdf->Cell(0, 10, $user->email, 0, 1);

$pdf->Cell(40, 10, 'Role:', 0);
$pdf->Cell(0, 10, $user->role, 0, 1);

$pdf->Cell(40, 10, 'Status:', 0);
$pdf->Cell(0, 10, $user->status, 0, 1);

$pdf->Ln(10);

// Subjects
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'List of Assigned Subjects', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);

if (empty($subjects)) {
    $pdf->Cell(0, 10, 'No subjects found.', 0, 1, 'C');
}
foreach ($subjects as $subject) {
    $pdf->Cell(40, 10, 'Subject Code:', 0);
    $pdf->Cell(0, 10, $subject->code, 0, 1);

    $pdf->Cell(40, 10, 'Subject Name:', 0);
    $pdf->Cell(0, 10, $subject->name, 0, 1);

    $pdf->Cell(40, 10, 'Course:', 0);
    $pdf->Cell(0, 10, Course::find($subject->course_id)->name, 0, 1);

    $pdf->Cell(40, 10, 'Year Level:', 0);
    $pdf->Cell(0, 10, $subject->year_level, 0, 1);

    $pdf->Ln(10);
}

ob_end_clean(); // Clear the output buffer
$pdf->Output('I', 'instructor_profile.pdf');
exit();
