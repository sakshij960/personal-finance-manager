<?php
session_start();
require_once __DIR__ . "/../db/connection.php";
//include("../config/db.php");

include("../includes/header.php");
//include("../includes/navbar.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message="";

// Fetch categories
$categories = mysqli_query($conn,"
    SELECT * FROM categories
    WHERE user_id='$user_id' OR user_id IS NULL
    ORDER BY category_id
");

if(isset($_POST['set_budget'])){

$category_id = $_POST['category_id'];
$limit = $_POST['limit'];
$month = $_POST['month'];
$year = $_POST['year'];

// check existing budget
$check = mysqli_query($conn,"
SELECT * FROM budgets 
WHERE user_id='$user_id'
AND category_id='$category_id'
AND month='$month'
AND year='$year'
");

if(mysqli_num_rows($check)>0){

mysqli_query($conn,"
UPDATE budgets
SET monthly_limit='$limit'
WHERE user_id='$user_id'
AND category_id='$category_id'
AND month='$month'
AND year='$year'
");

$message="Budget updated successfully";

}else{

mysqli_query($conn,"
INSERT INTO budgets(user_id,category_id,monthly_limit,month,year)
VALUES('$user_id','$category_id','$limit','$month','$year')
");

$message="Budget set successfully";

}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Set Budget</title>

<style>

body{
font-family:Arial;
background:#f4f6fb;
padding:40px;
}

.container{
max-width:500px;
margin:auto;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

h2{
margin-bottom:20px;
}

input,select{
width:100%;
padding:10px;
margin:10px 0;
border:1px solid #ccc;
border-radius:5px;
}

button{
background:#4e73df;
color:white;
padding:10px;
border:none;
width:100%;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#2e59d9;
}

.msg{
color:green;
margin-top:10px;
}

.back{
display:inline-block;
margin-top:15px;
text-decoration:none;
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

<h2>Set Monthly Budget</h2>

<form method="POST">

<label>Category</label>
<select name="category_id" required>

<?php while($row=mysqli_fetch_assoc($categories)){ ?>

<option value="<?php echo htmlspecialchars($row['category_id']); ?>">
<?php echo htmlspecialchars($row['category_name']); ?>
</option>

<?php } ?>

</select>

<label>Budget Limit</label>
<input type="number" name="limit" required>

<label>Month</label>
<select name="month">

<?php for($m=1;$m<=12;$m++){ ?>
<option value="<?php echo $m ?>"><?php echo $m ?></option>
<?php } ?>

</select>

<label>Year</label>
<input type="number" name="year" value="<?php echo date('Y') ?>">

<button name="set_budget">Save Budget</button>

</form>

<?php if($message){ ?>
<p class="msg"><?php echo htmlspecialchars($message) ?></p>
<?php } ?>

<a class="back" href="../dashboard.php">← Back to Dashboard</a>

</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>