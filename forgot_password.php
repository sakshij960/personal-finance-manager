<?php
include("db/connection.php");
?>

<!DOCTYPE html>
<html>
<head>

<title>Forgot Password</title>

<style>

body{
font-family:Arial;
background:linear-gradient(to right,#4e73df,#1cc88a);
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.box{
background:white;
padding:40px;
border-radius:10px;
width:350px;
box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

h3{
text-align:center;
margin-bottom:20px;
}

input{
width:100%;
padding:10px;
margin:10px 0;
border-radius:5px;
border:1px solid #ccc;
}

button{
width:100%;
padding:10px;
background:#4e73df;
border:none;
color:white;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#2e59d9;
}

</style>

</head>

<body>

<div class="box">

<h3>Forgot Password</h3>

<form action="reset_password.php" method="POST">

<input type="email" name="email" placeholder="Enter your email" required>

<button type="submit">Continue</button>

</form>

</div>

</body>
</html>