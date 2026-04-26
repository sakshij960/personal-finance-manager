<?php
session_start();
require_once __DIR__ . "/../db/connection.php";

include("../includes/header.php");
//include("../includes/navbar.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,"
SELECT b.*, c.category_name,
IFNULL(SUM(e.amount),0) AS spent
FROM budgets b
JOIN categories c ON b.category_id = c.category_id
LEFT JOIN expenses e 
ON e.category_id=b.category_id 
AND MONTH(e.date)=b.month
AND YEAR(e.date)=b.year
AND e.user_id=b.user_id
WHERE b.user_id='$user_id'
GROUP BY b.budget_id
ORDER BY b.year DESC, b.month DESC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>View Budgets</title>

<style>

body{
font-family:Arial;
background:#f4f6fb;
padding:40px;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

th,td{
padding:12px;
text-align:center;
border-bottom:1px solid #eee;
}

th{
background:#4e73df;
color:white;
}

.progress{
height:8px;
background:#ddd;
border-radius:5px;
overflow:hidden;
}

.bar{
height:100%;
background:#e74a3b;
}

.back{
display:inline-block;
margin-top:20px;
text-decoration:none;
background:#4e73df;
color:white;
padding:10px 15px;
border-radius:5px;
}

</style>
</head>

<body>

<div class="layout">

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="content">

<h2>Budget Overview</h2>

<table>

<tr>
<th>Category</th>
<th>Month</th>
<th>Year</th>
<th>Budget</th>
<th>Spent</th>
<th>Remaining</th>
<th>Usage</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)){

$remaining = $row['monthly_limit'] - $row['spent'];
$percent = ($row['spent']/$row['monthly_limit'])*100;
if($percent>100) $percent=100;

?>

<tr>

<td><?php echo htmlspecialchars($row['category_name']) ?></td>
<td><?php echo htmlspecialchars($row['month']) ?></td>
<td><?php echo htmlspecialchars($row['year']) ?></td>

<td>₹ <?php echo number_format($row['monthly_limit'],2) ?></td>

<td>₹ <?php echo number_format($row['spent'],2) ?></td>

<td>₹ <?php echo number_format($remaining,2) ?></td>

<td>

<div class="progress">
<div class="bar" style="width:<?php echo $percent ?>%"></div>
</div>

<?php // include("../includes/footer.php"); ?>

<?php echo round($percent) ?>%

</td>

</tr>

<?php } ?>

</table>

<br>

<a class="back" href="../dashboard.php">← Back to Dashboard</a>

<?php include("../includes/footer.php"); ?>

</body>
</html>