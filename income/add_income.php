<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
require_once __DIR__ . "/../db/connection.php";

include("../includes/header.php");
//include("../includes/navbar.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

if(isset($_POST['add_income'])){

    $amount = $_POST['amount'];
    $source = mysqli_real_escape_string($conn, $_POST['source']);
    $date = $_POST['date'];

    // Insert income
    $query = "INSERT INTO income (user_id, amount, source, date)
              VALUES ('$user_id', '$amount', '$source', '$date')";
    
    if(mysqli_query($conn, $query)){

        // Update balance
        mysqli_query($conn,
        "UPDATE balances 
         SET current_balance = current_balance + $amount 
         WHERE user_id = $user_id");

        $message = "Income Added Successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Income</title>
    <style>
        body{font-family:Arial;background:#f8f9fc;padding:30px;}
        .box{
            max-width:400px;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }
        input,button{
            width:100%;
            padding:10px;
            margin:10px 0;
        }
        button{
            background:#1cc88a;
            color:white;
            border:none;
            cursor:pointer;
        }
    </style>
</head>
<body>

<div class="layout">

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="content">

<div class="box">
<h2>Add Income</h2>

<?php if($message!=""){ echo "<p style='color:green;'>$message</p>"; } ?>

<form method="POST">
    <input type="number" name="amount" placeholder="Amount" required>
    <input type="text" name="source" placeholder="Source (Salary, Business)" required>
    <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" required>
    <button type="submit" name="add_income">Add Income</button>
</form>

<a href="../dashboard.php">← Back to Dashboard</a>
</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>