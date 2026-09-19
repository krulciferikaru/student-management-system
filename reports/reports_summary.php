<?php
// GRADES SUMMARY REPORT (SPECIFIC SUBJECT)


require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/Grade.php';
require_once '../models/Subject.php';
require_once '../models/Course.php';
require_once '../models/User.php';
require_once('../plugins/FPDF/fpdf.php'); 

    session_start();
    if(!isset($_SESSION['email'])){
        header('Location: ../auth/login.php');
     } 
    
$db = new Database();
$conn = $db->getConnection();
Grade::setConnection($conn);
Subject::setConnection($conn);

$id = $_GET['id'] ?? null;

$user = User::findEmail($_SESSION['email']);
$grade = Grade::find($id);
$subject = Subject::find($id);
    $students = $subject->students();


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(200, 10, 'Grades Summary', 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, 'Instructor Name: ' . $user->name, 0, 1);
$pdf->Cell(100, 10, 'Subject Name: ' . $subject->name, 0, 1);
$pdf->Cell(100, 10, 'Catalog No.: ' . $subject->catalog_no, 0, 1);
$pdf->Cell(100, 10, 'Number of Students: ' . count($subject->students()), 0, 1);
$pdf->Cell(100, 10, 'Passed: ' . Grade::remarks($subject->id, 'Passed') , 0, 1);
$pdf->Cell(100, 10, 'Failed: ' . Grade::remarks($subject->id, 'Failed') , 0, 1);
$pdf->Cell(100, 10, 'Pending: ' . Grade::remarks($subject->id, 'Pending') , 0, 1);
$pdf->Cell(100, 10, 'Incomplete: ' . Grade::remarks($subject->id, 'Incomplete') , 0, 1);


$pdf->Output();
?>
