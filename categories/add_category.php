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
$message="";

/* ADD CATEGORY */
/*if(isset($_POST['add_category'])){

$name = mysqli_real_escape_string($conn,$_POST['category_name']);
$type = $_POST['type'];

/* check duplicate */
/* $check = mysqli_query($conn,"
SELECT * FROM categories
WHERE category_name='$name'
AND (user_id='$user_id') "); */

if(isset($_POST['add_category'])){

    $name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $type = $_POST['type'];
    $user_id = $_SESSION['user_id'];

    // Validation
    if(empty($name)){
        $message = "Category name is required";
    } else {

        // Check duplicate
        $check = mysqli_query($conn,"
            SELECT * FROM categories
            WHERE category_name='$name'
            AND (user_id='$user_id' OR user_id IS NULL)
        ");

        if(mysqli_num_rows($check) > 0){

            $message = "Category already exists";

        } else {

            // Insert category
            $insert = mysqli_query($conn,"
                INSERT INTO categories(category_name,type,user_id)
                VALUES('$name','$type','$user_id')
            ");

            if($insert){
                $message = "Category added successfully";
            } else {
                $message = "Error adding category";
            }
        }
    }
}



/* DELETE CATEGORY */

if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM categories WHERE category_id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();

    header("Location: add_category.php");
}

/* FETCH CATEGORIES */

$categories = mysqli_query($conn,"
SELECT * FROM categories
WHERE user_id IS NULL
OR user_id='$user_id'
ORDER BY category_id ASC
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Categories</title>

<style>

body{
font-family:Arial;
background:#f4f6fb;
padding:40px;
}

/* container */

.container{
max-width:800px;
margin:auto;
}

/* card */

.card{
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 4px 10px rgba(0,0,0,0.08);
margin-bottom:25px;
}

h2{
margin-bottom:15px;
}

/* form */

input,select{
width:100%;
padding:10px;
margin-top:8px;
margin-bottom:15px;
border:1px solid #ccc;
border-radius:6px;
}

button{
background:#4e73df;
color:white;
padding:10px 15px;
border:none;
border-radius:6px;
cursor:pointer;
}

button:hover{
background:#2e59d9;
}

/* message */

.msg{
color:green;
font-weight:600;
margin-bottom:10px;
}

/* table */

table{
width:100%;
border-collapse:collapse;
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
background:#f9f9f9;
}

/* delete button */

.delete{
background:#e74a3b;
color:white;
padding:6px 10px;
border-radius:5px;
text-decoration:none;
}

.delete:hover{
background:#c0392b;
}

/* badge */

.badge{
padding:4px 8px;
border-radius:5px;
font-size:12px;
}

.default{
background:#d1ecf1;
color:#0c5460;
}

.custom{
background:#d4edda;
color:#155724;
}

/* back button */

.back{
display:inline-block;
margin-top:20px;
background:#6c757d;
color:white;
padding:8px 12px;
border-radius:6px;
text-decoration:none;
}

.back:hover{
background:#5a6268;
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

<!-- Add Category -->

<div class="card">

<h2>Add New Category</h2>

<?php if($message){ ?>
<p class="msg"><?php echo $message ?></p>
<?php } ?>

<form method="POST">

<label>Category Name</label>
<input type="text" name="category_name" required>

<label>Type</label>

<select name="type">

<option value="expense">Expense</option>
<option value="income">Income</option>

</select>

<button name="add_category">Add Category</button>

</form>

</div>

<!-- Category List -->

<div class="card">

<h2>All Categories</h2>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Type</th>
<th>Category Type</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($categories)){ ?>

<tr>

<td><?php echo htmlspecialchars($row['category_id']); ?></td>

<td><?php echo htmlspecialchars($row['category_name']); ?></td>

<td><?php echo ucfirst($row['type']); ?></td>

<td>

<?php if($row['user_id']==NULL){ ?>

<span class="badge default">Default</span>

<?php } else { ?>

<span class="badge custom">Custom</span>

<?php } ?>

</td>

<td>

<?php if($row['user_id']==$user_id){ ?>

<a class="delete"
href="add_category.php?delete=<?php echo htmlspecialchars($row['category_id']); ?>"
onclick="return confirm('Delete this category?')">
Delete
</a>

<?php } else { ?>

—

<?php } ?>

</td>

</tr>

<?php } ?>

</table>

</div>

<a class="back" href="../dashboard.php">← Back to Dashboard</a>

</div>

<?php include("../includes/footer.php"); ?>

</body>
</html>