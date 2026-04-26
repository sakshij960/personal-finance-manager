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

if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);
    $user_id = $_SESSION['user_id'];

    $delete = mysqli_query($conn,"
        DELETE FROM expenses 
        WHERE expense_id='$id' 
        AND user_id='$user_id'
    ");

    if($delete){
        header("Location: view_expense.php");
        exit();
    } else {
        echo "Delete failed: " . mysqli_error($conn);
    }
}

/* Fetch expenses */

$query = "SELECT expenses.*, categories.category_name
          FROM expenses
          JOIN categories ON expenses.category_id = categories.category_id
          WHERE expenses.user_id='$user_id'
          ORDER BY expenses.date DESC";

$result = mysqli_query($conn,$query);

/* Fetch categories for dropdown */

$cat_query = mysqli_query($conn,"
SELECT * FROM categories
WHERE user_id IS NULL OR user_id='$user_id'
ORDER BY category_id
");
?>

<!DOCTYPE html>
<html>
<head>

<title>View Expenses</title>

<style>

body{
font-family:Arial;
background:#f4f4f4;
}

.container{
width:90%;
margin:30px auto;
background:white;
padding:20px;
border-radius:8px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

h2{
text-align:center;
}

/* filter */

.filter-box{
text-align:center;
margin-bottom:20px;
}

input,select{
padding:6px;
margin:5px;
border:1px solid #ccc;
border-radius:4px;
}

button{
padding:6px 10px;
background:#ff6b6b;
color:white;
border:none;
border-radius:4px;
cursor:pointer;
}

/* table */

table{
width:100%;
border-collapse:collapse;
}

th,td{
padding:10px;
border:1px solid #ddd;
text-align:center;
}

th{
background:#ff6b6b;
color:white;
}

.delete-btn{
color:red;
text-decoration:none;
}

.back{
margin-top:15px;
text-align:center;
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

<h2>Your Expenses</h2>

<!-- FILTERS -->

<div class="filter-box">

<input type="text" id="search" placeholder="Search description...">

<select id="category">

<option value="">All Categories</option>

<?php
while($cat = mysqli_fetch_assoc($cat_query)){
?>

<option value="<?php echo $cat['category_id']; ?>">
<?php echo $cat['category_name']; ?>
</option>

<?php } ?>

</select>

</div>

<table>

<tr>
<th>ID</th>
<th>Category</th>
<th>Amount</th>
<th>Date</th>
<th>Description</th>
<th>Action</th>
</tr>

<tbody id="expenseTable">

<?php
if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){
?>

<tr>

<td><?php echo htmlspecialchars($row['expense_id']); ?></td>
<td><?php echo htmlspecialchars($row['category_name']); ?></td>
<td><?php echo htmlspecialchars($row['amount']); ?></td>
<td><?php echo htmlspecialchars($row['date']); ?></td>
<td><?php echo htmlspecialchars($row['description']); ?></td>

<td>
<a class="delete-btn"
href="view_expense.php?delete=<?php echo htmlspecialchars($row['expense_id']); ?>"
onclick="return confirm('Delete this expense?')">
Delete
</a>
</td>

</tr>

<?php
}

}else{

echo "<tr><td colspan='6'>No expenses found</td></tr>";

}
?>

</tbody>

</table>

<div class="back">
<a href="../dashboard.php">← Back to Dashboard</a>
</div>

</div>

<?php include("../includes/footer.php"); ?>

<script>

/* AJAX FUNCTION */

function loadExpenses(){

var search = document.getElementById("search").value;
var category = document.getElementById("category").value;

var xhr = new XMLHttpRequest();

xhr.open("POST","search_expense.php",true);

xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

xhr.onload=function(){

document.getElementById("expenseTable").innerHTML=this.responseText;

};

xhr.send("search="+search+"&category="+category);

}

/* LIVE SEARCH */

document.getElementById("search").addEventListener("keyup",loadExpenses);

/* CATEGORY FILTER */

document.getElementById("category").addEventListener("change",loadExpenses);

</script>

</body>
</html>