<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
require_once __DIR__ . "/../db/connection.php";

include("../includes/header.php");
//include("../includes/navbar.php");

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}
$message= "";
$error = "";
$user_id = $_SESSION['user_id'];

$cat_query = "
SELECT * FROM categories
WHERE user_id IS NULL
OR user_id='$user_id'
AND type='expense'
ORDER BY category_name
";

$cat_result = mysqli_query($conn, $cat_query);


if(isset($_POST['add_expense'])){

$category_id = $_POST['category_id'];
$amount = $_POST['amount'];
$date = $_POST['date'];
$description = $_POST['description'];

$month = date('n', strtotime($date));
$year = date('Y', strtotime($date));

/* Get budget for this category */
$budget_query = mysqli_query($conn,"
SELECT monthly_limit
FROM budgets
WHERE user_id='$user_id'
AND category_id='$category_id'
AND month='$month'
AND year='$year'
");

$budget = mysqli_fetch_assoc($budget_query);

if($budget){

$limit = $budget['monthly_limit'];

/* Calculate current spending */
$spent_query = mysqli_query($conn,"
SELECT IFNULL(SUM(amount),0) as spent
FROM expenses
WHERE user_id='$user_id'
AND category_id='$category_id'
AND MONTH(date)='$month'
AND YEAR(date)='$year'
");

$spent = mysqli_fetch_assoc($spent_query)['spent'];

$new_total = $spent + $amount;
$percent = ($new_total / $limit) * 100;

if($percent >= 100){
$warning="🚨 This expense will exceed your budget!";
}
elseif($percent >= 90){
$warning="⚠ This expense will nearly reach your budget!";
}

}

/* Insert expense */

mysqli_query($conn,"INSERT INTO expenses(user_id,category_id,amount,date,description)VALUES('$user_id','$category_id','$amount','$date','$description')");

//echo "<script>alert('Expense added successfully');</script>";
$message = "Expense Added Successfully!";
/*if(mysqli_query($conn, $query)){

    // Update balance (subtract expense)
    mysqli_query($conn,"
        UPDATE balances 
        SET current_balance = current_balance - $amount 
        WHERE user_id = $user_id
    ");

    $message = "Expense Added Successfully!";
}*/

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Expense</title>
    <style>
        body{
            font-family: Arial;
            background:#f4f4f4;
        }
        .container{
            width:400px;
            margin:50px auto;
            background:white;
            padding:20px;
            border-radius:8px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }
        h2{
            text-align:center;
        }
        input, select, textarea{
            width:100%;
            padding:8px;
            margin:10px 0;
        }
        button{
            width:100%;
            padding:10px;
            background:#ff6b6b;
            color:white;
            border:none;
            cursor:pointer;
        }
        .back{
            text-align:center;
            margin-top:10px;
        }
        .add-btn{
            background:#4e73df;
            color:white;
            padding:8px 12px;
            border-radius:5px;
            text-decoration:none;
        }

        .warning-box{
        background:#fff3cd;
        color:#856404;
        padding:12px;
        border-radius:8px;
        margin-bottom:15px;
        font-weight:600;
        }

        .warning-box.danger{
        background:#f8d7da;
        color:#721c24;
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

    <a class="add-btn" href="view_expense.php">+ View Expense</a>

    <h2>Add Expense</h2>

    <form method="POST">

        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php while($row = mysqli_fetch_assoc($cat_result))
            {
                echo "<option value='". htmlspecialchars($row['category_id'])."'>". htmlspecialchars($row['category_name'])."</option>";
            }
            ?>
        </select>

        <label>Amount</label>
        <input type="number" name="amount" step="0.01" required>

        <label>Date</label>
        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <button type="submit" name="add_expense">Add Expense</button>

    </form>

    <?php if(!empty($warning)){ ?>

    <div class="warning-box">
    <?php echo $warning; ?>
    </div>

    <?php } ?>

    <div class="back">
        <a href="../dashboard.php">Back to Dashboard</a>
    </div>

</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>