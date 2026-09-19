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

$user = User::findEmail($_SESSION['email']);

$subject_id = $_GET['id'] ?? null;




$subject = Subject::find($subject_id);
$students = $subject->gradedStudents();




$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(200, 10, 'Grades Summary', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(17, 10, 'Subject:', 0);
$pdf->Cell(0, 10, $subject->catalog_no, 0, 1);

$pdf->Cell(20, 10, 'Instructor:', 0);
$pdf->Cell(0, 10, $user->name, 0, 1);

$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30,10,'No.', 1, 0, 'C');
$pdf->Cell(40,10,'Student ID', 1, 0, 'C');
$pdf->Cell(50,10,'Name', 1, 0, 'C');
$pdf->Cell(40,10,'Grade', 1, 0, 'C');
$pdf->Cell(30,10,'Remarks', 1, 1, 'C');

$pdf->SetFont('Arial', '', 12);

if (count($students) > 0){
    $i = 1;
    foreach($students as $student){
        $pdf->Cell(30,10,$i++, 1, 0, 'C');
        $pdf->Cell(40,10,$student->student_id, 1, 0, 'C');
        $pdf->Cell(50,10,$student->name, 1, 0, 'C');
        $pdf->Cell(40,10,$student->studentGrade($subject_id)->grade, 1, 0, 'C');
        $pdf->Cell(30,10,$student->studentGrade($subject_id)->remarks, 1, 1, 'C');
    }
}else {
    $pdf->Cell(0, 10, 'No student records found in ' . $subject->code, 0, 1, 'C');
}


$pdf->Output();
?>



