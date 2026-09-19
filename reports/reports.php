<?php
// GRADES REPORT SUMMARY (ALL SUBJECTS)
session_start();

require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/Grade.php';
require_once '../models/Subject.php';
require_once '../models/Course.php';
require_once '../models/User.php';
require_once('../plugins/FPDF/fpdf.php'); 

 

$db = new Database();
$conn = $db->getConnection();
Grade::setConnection($conn);
Subject::setConnection($conn);

$user = User::findEmail($_SESSION['email']);

$id = $_GET['id'] ?? null;

$student = Grade::find($id);
$allSubjects = Subject::all();

$subjects = array_filter($allSubjects, function($subject) use ($id) {
    return $subject->instructor_id == $id;
});



$pdf = new FPDF();
$pdf->AddPage('L');
$pdf->SetFont('Times', 'B', 16);

$pdf->Cell(260, 10, 'Grades Summary', 0, 1, 'C');

$pdf->SetFont('Times', '', 12);

$pdf->Cell(20, 10, 'Instructor:', 0);
$pdf->Cell(0, 10, $user->name, 0, 1);

$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(90, 10, 'Subject Name', 1, 0, 'C');
$pdf->Cell(50, 10, 'Catalog No', 1, 0, 'C');
$pdf->Cell(45, 10, 'Number of Students', 1, 0, 'C');
$pdf->Cell(20, 10, 'Passed' , 1, 0,'C');
$pdf->Cell(20, 10, 'Failed' , 1, 0,'C');
$pdf->Cell(20, 10, 'Pending'  , 1, 0,'C');
$pdf->Cell(25, 10, 'Incomplete ' , 1, 1,'C');

$pdf->SetFont('Arial', '', 12);
foreach ($subjects as $subject) {
    $pdf->Cell(90, 10,  $subject->name, 1, 0, 'C');
    $pdf->Cell(50, 10, $subject->catalog_no, 1, 0, 'C');
 $pdf->Cell(45, 10, count($subject->students()), 1, 0, 'C');
    $pdf->Cell(20, 10, Grade::passed($subject->id) , 1, 0,'C');
    $pdf->Cell(20, 10,  Grade::failed($subject->id) , 1, 0, 'C');
    $pdf->Cell(20, 10, Grade::pending($subject->id)  , 1, 0, 'C');
    $pdf->Cell(25, 10,  Grade::incomplete($subject->id) , 1, 1, 'C');
}


$pdf->Output();
?>
