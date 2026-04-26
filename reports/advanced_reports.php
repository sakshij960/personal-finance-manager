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

/* =====================
   TREND DATA
===================== */

$trend_query = mysqli_query($conn,"
SELECT DATE_FORMAT(date,'%Y-%m') as month,
SUM(amount) total
FROM expenses
WHERE user_id='$user_id'
GROUP BY month
ORDER BY month
");

$months=[];
$totals=[];

while($row=mysqli_fetch_assoc($trend_query)){
$months[]=$row['month'];
$totals[]=$row['total'];
}

/* =====================
   SMART INSIGHTS
===================== */

$top_cat = mysqli_query($conn,"
SELECT c.category_name,SUM(e.amount) total
FROM expenses e
JOIN categories c ON e.category_id=c.category_id
WHERE e.user_id='$user_id'
GROUP BY e.category_id
ORDER BY total DESC
LIMIT 1
");

$top=mysqli_fetch_assoc($top_cat);

$avg_query=mysqli_query($conn,"
SELECT AVG(amount) avg_expense
FROM expenses
WHERE user_id='$user_id'
");

$avg=mysqli_fetch_assoc($avg_query)['avg_expense'];

/* =====================
   DATE RANGE REPORT
===================== */

$from=$_GET['from'] ?? '';
$to=$_GET['to'] ?? '';

$query="
SELECT e.*,c.category_name
FROM expenses e
JOIN categories c ON e.category_id=c.category_id
WHERE e.user_id='$user_id'
";

if($from && $to){
$query .= " AND date BETWEEN '$from' AND '$to'";
}

$query .= " ORDER BY date DESC";

$result=mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>
<head>

<title>Advanced Reports</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
font-family:Arial;
background:#f4f6fb;
padding:30px;
}

.container{
max-width:1100px;
margin:auto;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.05);
margin-bottom:25px;
}

.insight{
background:#e8f5e9;
padding:10px;
margin-top:8px;
border-radius:6px;
}

input{
padding:8px;
margin:5px;
}

button{
padding:8px 12px;
background:#4e73df;
color:white;
border:none;
border-radius:5px;
}

table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}

th,td{
padding:10px;
border-bottom:1px solid #eee;
text-align:center;
}

th{
background:#4e73df;
color:white;
}

.btn-danger {
    margin: 10px 0 20px 0;
    display: inline-block;
    background: #ef4444;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
}

</style>

</head>

<body>

<div class="layout">

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="content">

<div class="container">

<h2>📊 Advanced Reports</h2>

<div style="text-align: right;">
<a href="export_pdf.php?type=monthly" class="btn btn-danger">
    📄 Export Monthly Report
</a>
</div>

<div class="card">

<h3>📈 Expense Trend</h3>

<canvas id="trendChart"></canvas>

</div>

<div class="card">

<h3>🧠 Smart Insights</h3>

<div class="insight">
💡 Highest spending category:
<strong><?php echo $top['category_name']; ?></strong>
</div>

<div class="insight">
📊 Average expense:
₹ <?php echo round($avg,2); ?>
</div>

</div>

<div class="card">

<h3>📅 Date Range Report</h3>

<form>

From
<input type="date" name="from" value="<?php echo $from ?>">

To
<input type="date" name="to" value="<?php echo $to ?>">

<button type="submit">Generate</button>

</form>

<table>

<tr>
<th>Date</th>
<th>Category</th>
<th>Description</th>
<th>Amount</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?php echo htmlspecialchars($row['date']); ?></td>
<td><?php echo htmlspecialchars($row['category_name']); ?></td>
<td><?php echo htmlspecialchars($row['description']); ?></td>
<td>₹ <?php echo number_format($row['amount'],2); ?></td>
</tr>

<?php } ?>

</table>

</div>

<a href="../dashboard.php">← Back to Dashboard</a>

</div>
<?php include("../includes/footer.php"); ?>

<script>

new Chart(document.getElementById('trendChart'),{
type:'line',
data:{
labels: <?php echo json_encode($months); ?>,
datasets:[{
label:'Expense Trend',
data: <?php echo json_encode($totals); ?>,
borderColor:'#ef4444',
fill:false,
tension:0.3
}]
}
});

</script>

</body>
</html>