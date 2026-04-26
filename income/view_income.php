<?php
session_start();
require_once __DIR__ . "/../db/connection.php";

include("../includes/header.php");
//include("../includes/navbar.php");

// Protect page
if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Delete income
if(isset($_GET['delete'])){
    $income_id = $_GET['delete'];

    // Get amount before deleting (to update balance)
    $get_amount = mysqli_query($conn,
    "SELECT amount FROM income 
     WHERE income_id='$income_id' AND user_id='$user_id'");
    
    $row = mysqli_fetch_assoc($get_amount);
    $amount = $row['amount'];

    // Delete income
    mysqli_query($conn,
    "DELETE FROM income 
     WHERE income_id='$income_id' AND user_id='$user_id'");

    // Update balance
    mysqli_query($conn,
    "UPDATE balances 
     SET current_balance = current_balance - $amount
     WHERE user_id = '$user_id'");

    header("Location: view_income.php");
    exit();
}

// Fetch income records
$income_query = mysqli_query($conn,
"SELECT * FROM income 
 WHERE user_id='$user_id' 
 ORDER BY date DESC");

// Total income
$total_query = mysqli_query($conn,
"SELECT IFNULL(SUM(amount),0) AS total_income 
 FROM income WHERE user_id='$user_id'");
$total_data = mysqli_fetch_assoc($total_query);
$total_income = $total_data['total_income'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Income</title>
    <style>
        body{
            font-family: Arial;
            background:#f8f9fc;
            padding:30px;
        }

        h2{
            margin-bottom:10px;
        }

        table{
            width:100%;
            border-collapse: collapse;
            background:white;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }

        th, td{
            padding:10px;
            border-bottom:1px solid #ddd;
            text-align:center;
        }

        th{
            background:#4e73df;
            color:white;
        }

        a.delete{
            color:white;
            background:#e74a3b;
            padding:5px 10px;
            border-radius:5px;
            text-decoration:none;
        }

        .top-section{
            display:flex;
            justify-content:space-between;
            margin-bottom:20px;
        }

        .total-box{
            background:#1cc88a;
            color:white;
            padding:10px 20px;
            border-radius:8px;
        }

        .add-btn{
            background:#4e73df;
            color:white;
            padding:8px 12px;
            border-radius:5px;
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

<div class="top-section">
    <div>
        <h2>Your Income Records</h2>
    </div>

    <div class="total-box">
        Total Income: ₹ <?php echo number_format($total_income,2); ?>
    </div>
</div>

<?php //include("../includes/footer.php"); ?>

<a class="add-btn" href="add_income.php">+ Add Income</a>
<br><br>

<table>
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Source</th>
        <th>Amount</th>
        <th>Action</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($income_query)){ ?>
    <tr>
        <td><?php echo htmlspecialchars($row['income_id']); ?></td>
        <td><?php echo htmlspecialchars($row['date']); ?></td>
        <td><?php echo htmlspecialchars($row['source']); ?></td>
        <td>₹ <?php echo number_format($row['amount'],2); ?></td>
        <td>
            <a class="delete" 
               href="view_income.php?delete=<?php echo $row['income_id']; ?>" 
               onclick="return confirm('Are you sure?')">
               Delete
            </a>
        </td>
    </tr>
    <?php } ?>

</table>

<br>
<a href="../dashboard.php">← Back to Dashboard</a>

<?php include("../includes/footer.php"); ?>

</body>
</html>