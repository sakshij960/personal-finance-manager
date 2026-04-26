<?php
include("db/connection.php");

$error="";
$email=$_POST['email'] ?? "";

if(isset($_POST['update_password'])){

$email=$_POST['email'];
$new_password=$_POST['new_password'];
$confirm_password=$_POST['confirm_password'];

if($new_password != $confirm_password){

$error="Passwords do not match!";

}else{

$password=password_hash($new_password,PASSWORD_DEFAULT);

mysqli_query($conn,"UPDATE users SET password='$password' WHERE email='$email'");

header("Location: index.php?msg=reset_success");
exit();

}

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Reset Password</title>

<style>

body{
font-family:Arial;
background:#f5f7fb;
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

*{
box-sizing:border-box;
}

h3{
text-align:center;
margin-bottom:20px;
}

form{
width:100%;
}

.password-box{
position:relative;
width:100%;
margin-bottom:15px;
}

.password-box input{
width:100%;
padding:10px;
padding-right:40px;
border:1px solid #ccc;
border-radius:5px;
}

.toggle-password{
position:absolute;
right:10px;
top:50%;
transform:translateY(-50%);
cursor:pointer;
font-size:18px;
}

button{
width:100%;
padding:10px;
background:#4e73df;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#2e59d9;
}

.error{
color:red;
text-align:center;
margin-bottom:10px;
}

</style>

</head>

<body>

<div class="box">

<h3>Reset Password</h3>

<?php if($error!=""){ ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>

<form method="POST">

<input type="hidden" name="email" value="<?php echo $email; ?>">

<div class="password-box">

<input type="password" id="password" name="new_password" placeholder="New Password" required>

<span class="toggle-password" onclick="togglePassword('password',this)">👁</span>

</div>

<div class="password-box">

<input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>

<span class="toggle-password" onclick="togglePassword('confirm_password',this)">👁</span>

</div>

<button type="submit" name="update_password">Update Password</button>

</form>

</div>

<script>

function togglePassword(fieldId,icon){

var input=document.getElementById(fieldId);

if(input.type==="password"){

input.type="text";
icon.innerHTML="🙈";

}else{

input.type="password";
icon.innerHTML="👁";

}

}

</script>

</body>
</html>