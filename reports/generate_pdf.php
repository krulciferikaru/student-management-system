<?php
require_once '../database/Database.php';
require_once '../models/Student.php';
require_once '../models/Subject.php';
require_once '../models/Course.php';
require_once '../plugins/FPDF/fpdf.php'; 
require_once '../models/User.php';

session_start();

$db = new Database();
$conn = $db->getConnection();
Student::setConnection($conn);

$student_id = $_GET['student_id'] ?? null;

if (!$student_id) {
    header('Location: ../404.php');
    exit;
}

$student = Student::find($student_id);

if (!$student) {
    header('Location: ../404.php');
    exit;
}

$query = "SELECT s.code, s.name, s.year_level, s.semester, u.name as instructor, g.grade, g.remarks 
          FROM grades g
          JOIN subjects s ON g.subject_id = s.id
          JOIN users u ON s.instructor_id = u.id
          WHERE g.student_id = :student_id";

$stmt = $conn->prepare($query);
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_STR);
$stmt->execute();
$grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'Student Report', 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(100, 8, 'Student ID: ' . $student->student_id, 0, 1);
$pdf->Cell(100, 8, 'Full Name: ' . $student->name, 0, 1);
$pdf->Cell(100, 8, 'Gender: ' . $student->gender, 0, 1);
$pdf->Cell(100, 8, 'Date of Birth: ' . $student->birthdate, 0, 1);
$pdf->Cell(100, 8, 'Course: ' . $student->course()->name, 0, 1);
$pdf->Cell(100, 8, 'Year Level: ' . $student->year_level, 0, 1);
$pdf->Cell(100, 8, 'Status: ' . $student->status, 0, 1);

$pdf->Ln(8);

// Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(25, 8, 'Code', 1, 0, 'C');
$pdf->Cell(45, 8, 'Subject Name', 1, 0, 'C');
$pdf->Cell(18, 8, 'Year', 1, 0, 'C');
$pdf->Cell(18, 8, 'Sem', 1, 0, 'C');
$pdf->Cell(40, 8, 'Instructor', 1, 0, 'C');
$pdf->Cell(20, 8, 'Grade', 1, 0, 'C');
$pdf->Cell(24, 8, 'Remarks', 1, 1, 'C');

$pdf->SetFont('Arial', '', 9);

foreach ($grades as $grade) {
    
    $remarks = $grade['remarks'] ?: 'No Remarks';

    $pdf->Cell(25, 8, $grade['code'], 1, 0, 'C');
    $pdf->Cell(45, 8, $grade['name'], 1, 0, 'C');
    $pdf->Cell(18, 8, $grade['year_level'], 1, 0, 'C');
    $pdf->Cell(18, 8, $grade['semester'], 1, 0, 'C');
    $pdf->Cell(40, 8, $grade['instructor'], 1, 0, 'C');
    $pdf->Cell(20, 8, $grade['grade'] ?? '-', 1, 0, 'C');
    $pdf->Cell(24, 8, $remarks, 1, 1, 'C');
}

$pdf->Output();
?>
