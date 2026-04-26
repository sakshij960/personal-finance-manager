<?php
session_start();
require_once __DIR__ . "/../db/connection.php";
//include("config/db.php");

include("../includes/header.php");
//include("../includes/navbar.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch category wise expenses
$query = mysqli_query($conn,"
SELECT c.category_name, SUM(e.amount) as total
FROM expenses e
JOIN categories c ON e.category_id = c.category_id
WHERE e.user_id='$user_id'
GROUP BY e.category_id
ORDER BY total DESC
");

// Store data
$categories = [];
$amounts = [];
$total_expense = 0;

while($row = mysqli_fetch_assoc($query)){
    $categories[] = $row['category_name'];
    $amounts[] = $row['total'];
    $total_expense += $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Category Expense Report</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
background:#f4f6fb;
padding:40px;
}

.container{
max-width:1100px;
margin:auto;
}

h1{
margin-bottom:25px;
color:#333;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
margin-bottom:25px;
}

.total{
font-size:26px;
font-weight:bold;
color:#e74a3b;
margin-top:10px;
}

.chart-box{
width:400px;
margin:auto;
}

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

th, td{
padding:12px;
text-align:center;
border-bottom:1px solid #eee;
}

th{
background:#4e73df;
color:white;
}

tr:hover{
background:#f2f2f2;
}

.back{
display:inline-block;
margin-top:20px;
padding:10px 15px;
background:#4e73df;
color:white;
text-decoration:none;
border-radius:5px;
}

</style>
</head>

<body>

<div class="container">

<h1>📂 Category Expense Report</h1>

<div class="card">

<h3>Total Expense</h3>
<div class="total">₹ <?php echo number_format($total_expense,2); ?></div>

</div>

<div class="card chart-box">
<canvas id="categoryChart"></canvas>
</div>

<div class="card">

<h3>Expense by Category</h3>

<table>

<tr>
<th>Category</th>
<th>Total Expense</th>
<th>Percentage</th>
</tr>

<?php
for($i=0;$i<count($categories);$i++){

$percent = ($amounts[$i] / $total_expense) * 100;

echo "<tr>
<td>{$categories[$i]}</td>
<td>₹ ".number_format($amounts[$i],2)."</td>
<td>".round($percent,1)." %</td>
</tr>";
}
?>

</table>

</div>

<a class="back" href="../dashboard.php">← Back to Dashboard</a>

</div>

<?php include("../includes/footer.php"); ?>

<script>

const ctx = document.getElementById('categoryChart');

new Chart(ctx,{
type:'pie',
data:{
labels: <?php echo json_encode($categories); ?>,
datasets:[{
data: <?php echo json_encode($amounts); ?>,
backgroundColor:[
'#4e73df',
'#1cc88a',
'#36b9cc',
'#f6c23e',
'#e74a3b',
'#858796'
]
}]
},
options:{
plugins:{
legend:{
position:'bottom'
}
}
}
});

</script>

</body>
</html>