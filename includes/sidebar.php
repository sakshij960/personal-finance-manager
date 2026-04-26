<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

<h2 class="logo">💳 FinTrack</h2>

<div class="profile">

<div class="avatar">
<?php echo strtoupper(substr($_SESSION['name'],0,1)); ?>
</div>

<div class="profile-name">
<?php echo $_SESSION['name']; ?>
</div>

</div>

<ul>

<li>
<a href="/personal_finance_manager/dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
🏠 Dashboard
</a>
</li>
<li>
<a href="/personal_finance_manager/income/add_income.php" class="<?php echo ($current_page == 'add_income.php') ? 'active' : ''; ?>">
💰 Add Income
</a>
</li>
<li>
<a href="/personal_finance_manager/income/view_income.php" class="<?php echo ($current_page == 'view_income.php') ? 'active' : ''; ?>">
📄 View Income
</a>
</li>

<li>
<a href="/personal_finance_manager/expense/add_expense.php" class="<?php echo ($current_page == 'add_expense.php') ? 'active' : ''; ?>">
💸 Add Expense
</a>
</li>

<li>
<a href="/personal_finance_manager/expense/view_expense.php" class="<?php echo ($current_page == 'view_expense.php') ? 'active' : ''; ?>">
📊 View Expenses
</a>
</li>

<li>
<a href="/personal_finance_manager/budget/set_budget.php" class="<?php echo ($current_page == 'set_budget.php') ? 'active' : ''; ?>">
🎯 Set Budget
</a>
</li>

<li>
<a href="/personal_finance_manager/budget/view_budget.php" class="<?php echo ($current_page == 'view_budget.php') ? 'active' : ''; ?>">
📉 Budget Status
</a>
</li>

<li>
<a href="/personal_finance_manager/categories/add_category.php" class="<?php echo ($current_page == 'add_category.php') ? 'active' : ''; ?>">
🗂 Categories
</a>
</li>

<li>
<a href="/personal_finance_manager/reports/advanced_reports.php" class="<?php echo ($current_page == 'advanced_reports.php') ? 'active' : ''; ?>">
📈 Reports
</a>
</li>

<li>
<a href="/personal_finance_manager/logout.php">
🚪 Logout
</a>
</li>

</ul>

</div>