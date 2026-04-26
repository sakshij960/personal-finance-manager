<?php
session_start();
include("db/connection.php");

if(!isset($_SESSION['user_id'])){
header("Location: ../index.php");
exit();
}

$user_id=$_SESSION['user_id'];
$name=$_SESSION['name'];

/* TOTALS */

$total_income=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT IFNULL(SUM(amount),0) t FROM income WHERE user_id='$user_id'"))['t'];

$total_expense=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT IFNULL(SUM(amount),0) t FROM expenses WHERE user_id='$user_id'"))['t'];

$current_balance=$total_income-$total_expense;

/* MONTHLY */

$monthly_income=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT IFNULL(SUM(amount),0) t FROM income 
WHERE user_id='$user_id' AND MONTH(date)=MONTH(CURRENT_DATE())"))['t'];

$monthly_expense=mysqli_fetch_assoc(mysqli_query($conn,
"SELECT IFNULL(SUM(amount),0) t FROM expenses 
WHERE user_id='$user_id' AND MONTH(date)=MONTH(CURRENT_DATE())"))['t'];

/* CATEGORY EXPENSE PIE */

$cat_query=mysqli_query($conn,"
SELECT c.category_name, IFNULL(SUM(e.amount),0) total
FROM categories c
LEFT JOIN expenses e
ON c.category_id=e.category_id
AND e.user_id='$user_id'
GROUP BY c.category_id
");

$cat_labels=[];
$cat_data=[];

while($row=mysqli_fetch_assoc($cat_query)){
$cat_labels[]=$row['category_name'];
$cat_data[]=(float)$row['total'];
}

/* BUDGET ALERTS */

$alerts=[];

$budget_query=mysqli_query($conn,"
SELECT b.monthly_limit,c.category_name,
IFNULL(SUM(e.amount),0) spent
FROM budgets b
JOIN categories c ON b.category_id=c.category_id
LEFT JOIN expenses e
ON e.category_id=b.category_id
AND MONTH(e.date)=MONTH(CURRENT_DATE())
AND YEAR(e.date)=YEAR(CURRENT_DATE())
AND e.user_id=b.user_id
WHERE b.user_id='$user_id'
AND b.month=MONTH(CURRENT_DATE())
AND b.year=YEAR(CURRENT_DATE())
GROUP BY b.category_id
");

while($row=mysqli_fetch_assoc($budget_query)){

$limit=$row['monthly_limit'];
$spent=$row['spent'];

if($limit>0){

$percent=($spent/$limit)*100;

if($percent>=100){
$alerts[]="🚨 ".$row['category_name']." budget exceeded!";
}
elseif($percent>=90){
$alerts[]="⚠ ".$row['category_name']." budget almost finished (".round($percent)."%)";
}

}

}


$spent_percent = ($total_income>0) ? ($total_expense/$total_income)*100 : 0;

/* Color Logic */

$color = "green";
$status="Good Saving";

if($spent_percent >= 80){
$color = "red";
$status="High Spending";
}
elseif($spent_percent >= 50){
$color = "orange";
$status="Moderate Spending";
}



/* EXPENSE TREND */

$trend=mysqli_query($conn,"
SELECT DATE(date) d, SUM(amount) total
FROM expenses
WHERE user_id='$user_id'
GROUP BY DATE(date)
ORDER BY DATE(date)
LIMIT 7
");

$dates=[];
$amounts=[];

while($row=mysqli_fetch_assoc($trend)){
$dates[]=$row['d'];
$amounts[]=$row['total'];
}

/* RECENT TRANSACTIONS */

$recent=mysqli_query($conn,"
SELECT 'Income' type,amount,date FROM income WHERE user_id='$user_id'
UNION
SELECT 'Expense',amount,date FROM expenses WHERE user_id='$user_id'
ORDER BY date DESC LIMIT 5
");

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

?>

<!DOCTYPE html>
<html>
<head>

<title>Dashboard</title>

<link rel="stylesheet" href="css/style.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="layout">

<?php include("includes/sidebar.php"); ?>

<div class="main">

<?php include("includes/topbar.php"); ?>

<div class="content">

<?php if(!empty($alerts)){ ?>

<div class="alert-box">

<?php foreach($alerts as $alert){ ?>

<div class="alert"><?php echo $alert; ?></div>

<?php } ?>

</div>

<?php } ?>

<!-- CARDS -->

<div class="cards">

<a href="income/add_income.php" class="card income">
<h4>Total Income</h4>
<div class="amount">₹ <?php echo number_format($total_income,2); ?></div>
</a>

<a href="expense/add_expense.php" class="card expense">
<h4>Total Expense</h4>
<div class="amount">₹ <?php echo number_format($total_expense,2); ?></div>
</a>

<div class="card balance">
<h4>Current Balance</h4>
<div class="amount">₹ <?php echo number_format($current_balance,2); ?></div>
</div>
</div>

<div class="ccard">

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

<div class="card savings-card">

<h4>Savings Indicator</h4>

<div class="progress-bar">

<div class="progress <?php echo $color; ?>" 
style="width:<?php echo $spent_percent ?>%">
</div>

</div>

<p><?php echo round($spent_percent) ?>% of income spent - <?php echo $status; ?></p>

</div>

<!-- CHARTS -->

<div class="chart-section">

<div class="chart-box">
<h4>Expense by Category</h4>
<canvas id="categoryChart" height="200"></canvas>
</div>

<div class="chart-box">
<h4>Income vs Expense (Monthly)</h4>
<canvas id="barChart" height="200"></canvas>
</div>

<div class="chart-box">
<h4>Expense Trend</h4>
<canvas id="lineChart" height="200"></canvas>
</div>

</div>

<!-- RECENT -->

<div class="table-box">

<h3>Recent Transactions</h3>

<table>

<tr>
<th>Type</th>
<th>Amount</th>
<th>Date</th>
</tr>

<?php while($r=mysqli_fetch_assoc($recent)){ ?>

<tr>

<td class="<?php echo strtolower($r['type']); ?>-text">
<?php echo $r['type']; ?>
</td>

<td>₹ <?php echo number_format($r['amount'],2); ?></td>

<td><?php echo $r['date']; ?></td>

</tr>

<?php } ?>

</table>

</div>

</div>
</div>
</div>

<script>

/* CATEGORY PIE */

new Chart(document.getElementById('categoryChart'),{
type:'pie',
data:{
labels: <?php echo json_encode($cat_labels); ?>,
datasets:[{
data: <?php echo json_encode($cat_data); ?>,
backgroundColor:[
'#6366f1',
'#ef4444',
'#10b981',
'#f59e0b',
'#3b82f6',
'#8b5cf6'
]
}]
},
options:{
plugins:{

/* legend hide */

legend:{
display:false
},

/* tooltip values */

tooltip:{
callbacks:{
label:function(context){

let value=context.raw;

let total=context.dataset.data.reduce((a,b)=>a+b,0);

let percent=((value/total)*100).toFixed(1);

return context.label+" : ₹"+value+" ("+percent+"%)";

}
}
}

}
}
});

/* BAR CHART */

new Chart(document.getElementById('barChart'),{
type:'bar',
data:{
labels:['Income','Expense'],
datasets:[{
label:'This Month',
data:[<?php echo $monthly_income ?>,<?php echo $monthly_expense ?>],
backgroundColor:['#3b82f6','#ef4444']
}]
}
});

/* LINE CHART */

new Chart(document.getElementById('lineChart'),{
type:'line',
data:{
labels: <?php echo json_encode($dates); ?>,
datasets:[{
label:'Expense Trend',
data: <?php echo json_encode($amounts); ?>,
borderColor:'#ef4444',
backgroundColor:'rgba(239,68,68,0.2)',
tension:0.4,
fill:true
}]
}
});

</script>

<?php include("includes/footer.php"); ?>

</body>
</html>