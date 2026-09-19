<?php

require_once '../database/Database.php';
require_once '../models/Course.php';
require '../plugins/fpdf/fpdf.php';
require_once '../models/Student.php';
require_once '../models/User.php';


$database = new Database();
$db = $database->getConnection();
Course::setConnection($db);
$course = Course::find($_GET['id']);
$code = $course->code;

if (!$course) {
    header("Location: ../404.php");
    exit;
}

$students = $course->student();
$nameofFile = $code .'-students-list.pdf';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 20);

$pdf->Cell(0, 10, $code . ' Student List', 0, 1, 'C');

$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30,10,'No.', 1, 0, 'C');
$pdf->Cell(40,10,'Student ID', 1, 0, 'C');
$pdf->Cell(50,10,'Name', 1, 0, 'C');
$pdf->Cell(40,10,'Gender', 1, 0, 'C');
$pdf->Cell(30,10,'Year', 1, 1, 'C');

$pdf->SetFont('Arial', '', 11);


if (count($students) > 0){
    $i = 1;
    foreach($students as $student){
        $pdf->Cell(30,10,$i++, 1, 0, 'C');
        $pdf->Cell(40,10,$student->student_id, 1, 0, 'C');
        $pdf->Cell(50,10,$student->name, 1, 0, 'C');
        $pdf->Cell(40,10,$student->gender, 1, 0, 'C');
        $pdf->Cell(30,10,$student->year_level, 1, 1, 'C');
    }
}else {
    $pdf->Cell(0, 10, 'No student records found in ' . $code, 0, 1, 'C');
}

$pdf->Output('I', $nameofFile);


