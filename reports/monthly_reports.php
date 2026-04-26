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

// Monthly income
$income_query = mysqli_query($conn,"
SELECT MONTH(date) as month, SUM(amount) as total
FROM income
WHERE user_id='$user_id'
GROUP BY MONTH(date)
ORDER BY MONTH(date)
");

// Monthly expense
$expense_query = mysqli_query($conn,"
SELECT MONTH(date) as month, SUM(amount) as total
FROM expenses
WHERE user_id='$user_id'
GROUP BY MONTH(date)
ORDER BY MONTH(date)
");

// Store results
$income_data = array_fill(1,12,0);
$expense_data = array_fill(1,12,0);

while($row=mysqli_fetch_assoc($income_query)){
    $income_data[$row['month']] = $row['total'];
}

while($row=mysqli_fetch_assoc($expense_query)){
    $expense_data[$row['month']] = $row['total'];
}

$months = [
1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",
7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec"
];

?>
<!DOCTYPE html>
<html>
<head>
<title>Monthly Financial Report</title>

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
margin-bottom:30px;
color:#333;
}

.cards{
display:flex;
gap:20px;
margin-bottom:30px;
flex-wrap:wrap;
}

.card{
flex:1;
min-width:220px;
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

.card h3{
font-size:16px;
color:#555;
}

.amount{
font-size:26px;
margin-top:10px;
font-weight:bold;
}

.income{
border-left:5px solid #28a745;
}

.expense{
border-left:5px solid #dc3545;
}

.balance{
border-left:5px solid #4e73df;
}

.chart-box{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
margin-bottom:30px;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 3px 10px rgba(0,0,0,0.1);
border-radius:10px;
overflow:hidden;
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

<h1>📊 Monthly Financial Report</h1>

<?php
$total_income = array_sum($income_data);
$total_expense = array_sum($expense_data);
$balance = $total_income - $total_expense;
?>

<div class="cards">

<div class="card income">
<h3>Total Income</h3>
<div class="amount">₹ <?php echo number_format($total_income,2); ?></div>
</div>

<div class="card expense">
<h3>Total Expense</h3>
<div class="amount">₹ <?php echo number_format($total_expense,2); ?></div>
</div>

<div class="card balance">
<h3>Total Balance</h3>
<div class="amount">₹ <?php echo number_format($balance,2); ?></div>
</div>

</div>

<div class="chart-box">
<canvas id="monthlyChart"></canvas>
</div>

<table>

<tr>
<th>Month</th>
<th>Income</th>
<th>Expense</th>
<th>Balance</th>
</tr>

<?php
for($i=1;$i<=12;$i++){

$inc = $income_data[$i];
$exp = $expense_data[$i];
$bal = $inc - $exp;

echo "<tr>
<td>{$months[$i]}</td>
<td>₹ ".number_format($inc,2)."</td>
<td>₹ ".number_format($exp,2)."</td>
<td>₹ ".number_format($bal,2)."</td>
</tr>";
}
?>

</table>

<a class="back" href="../dashboard.php">← Back to Dashboard</a>

</div>

<?php include("../includes/footer.php"); ?>

<script>

const ctx = document.getElementById('monthlyChart');

new Chart(ctx,{
type:'bar',
data:{
labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
datasets:[
{
label:'Income',
data:[<?php echo implode(',',$income_data); ?>],
backgroundColor:'#28a745'
},
{
label:'Expense',
data:[<?php echo implode(',',$expense_data); ?>],
backgroundColor:'#dc3545'
}
]
},
options:{
responsive:true,
plugins:{
legend:{position:'top'}
}
}
});

</script>

</body>
</html>