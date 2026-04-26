<?php
session_start();
require_once __DIR__ . "/../db/connection.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../index.php");
exit();
}

$user_id = $_SESSION['user_id'];

$search = $_POST['search'] ?? '';
$category = $_POST['category'] ?? '';

$query = "SELECT expenses.*, categories.category_name
          FROM expenses
          JOIN categories ON expenses.category_id = categories.category_id
          WHERE expenses.user_id='$user_id'";

/* search */

if($search){
$query .= " AND expenses.description LIKE '%$search%'";
}

/* category filter */

if($category){
$query .= " AND expenses.category_id='$category'";
}

$query .= " ORDER BY expenses.date DESC";

$result = mysqli_query($conn,$query);

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
href="delete_expense.php?id=<?php echo htmlspecialchars($row['expense_id']); ?>"
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