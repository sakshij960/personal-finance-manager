<?php
ob_start();
require('../fpdf/fpdf.php');
include("../db/connection.php");
session_start();

$user_id = $_SESSION['user_id'];

$month = date('m');
$year = date('Y');

$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,'Monthly Financial Report',0,1,'C');

$pdf->Ln(5);

/* =====================
   INCOME TABLE
===================== */

$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,10,'Income',0,1);

$pdf->SetFont('Arial','B',10);

// Table Header
$pdf->Cell(60,8,'Date',1);
$pdf->Cell(60,8,'Source',1);
$pdf->Cell(60,8,'Amount (INR)',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);

$total_income = 0;

$income = mysqli_query($conn,"
SELECT * FROM income 
WHERE user_id='$user_id' 
AND MONTH(date)='$month' 
AND YEAR(date)='$year'
");

while($row=mysqli_fetch_assoc($income)){
    $pdf->Cell(60,8,$row['date'],1);
    $pdf->Cell(60,8,$row['source'],1);
    $pdf->Cell(60,8,$row['amount'],1);
    $pdf->Ln();

    $total_income += $row['amount'];
}

/* =====================
   EXPENSE TABLE
===================== */

$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,10,'Expenses',0,1);

$pdf->SetFont('Arial','B',10);

// Table Header
$pdf->Cell(50,8,'Date',1);
$pdf->Cell(50,8,'Category',1);
$pdf->Cell(50,8,'Description',1);
$pdf->Cell(40,8,'Amount (INR)',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);

$total_expense = 0;

$expense = mysqli_query($conn,"
SELECT e.*,c.category_name 
FROM expenses e
JOIN categories c ON e.category_id=c.category_id
WHERE e.user_id='$user_id'
AND MONTH(e.date)='$month'
AND YEAR(e.date)='$year'
");

while($row=mysqli_fetch_assoc($expense)){
    $pdf->Cell(50,8,$row['date'],1);
    $pdf->Cell(50,8,$row['category_name'],1);
    $pdf->Cell(50,8,$row['description'],1);
    $pdf->Cell(40,8,$row['amount'],1);
    $pdf->Ln();

    $total_expense += $row['amount'];
}

/* =====================
   SUMMARY
===================== */

$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);

$pdf->Cell(190,10,"Total Income: Rs. $total_income",0,1);
$pdf->Cell(190,10,"Total Expense: Rs. $total_expense",0,1);
$pdf->Cell(190,10,"Savings: Rs. ".($total_income - $total_expense),0,1);

$pdf->Output();
ob_end_flush();
?>