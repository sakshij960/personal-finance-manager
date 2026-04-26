<?php
session_start();
include("db/connection.php");

/*if(!isset($_SESSION['user_id'])){
header("Location: index.php");
exit();
}*/

$message = "";
$error = "";

if(isset($_POST['register'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password match check
    if($password !== $confirm_password){
        $error = "Passwords do not match!";
    } else {

        // Check if email already exists
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0){
            $error = "Email already registered!";
        } else {

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $insert_user = "INSERT INTO users (name, email, password) 
                            VALUES ('$name', '$email', '$hashed_password')";
            
            if(mysqli_query($conn, $insert_user)){

                $user_id = mysqli_insert_id($conn);

                // Create balance record
                mysqli_query($conn, 
                "INSERT INTO balances (user_id, current_balance) 
                 VALUES ('$user_id', 0.00)");

                $_SESSION['user_id'] = $user_id;
                $_SESSION['name'] = $name;

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Something went wrong!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Personal Finance Manager</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #36b9cc, #1cc88a);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-box{
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            width: 380px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .register-box h2{
            text-align: center;
            margin-bottom: 20px;
        }

        input{
            width: 100%;
            padding: 10px;
            padding-right: 40px; /* keep space for icon */
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button{
            width: 100%;
            padding: 10px;
            background: #1cc88a;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover{
            background: #17a673;
        }

        .error{
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .success{
            color: green;
            text-align: center;
            margin-bottom: 10px;
        }

        .login-link{
            text-align: center;
            margin-top: 15px;
        }

        .login-link a{
            color: #36b9cc;
            text-decoration: none;
        }

        .password-box{
        position: relative;
        width: 100%;
        }

        .password-box input{
            width: 100%;
            /*padding: 10px;*/
            padding-right: 40px; /* space for eye icon */
            box-sizing: border-box;
        }

        .toggle-password{
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Create Account</h2>

    <?php if($error != ""){ ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <?php if($message != ""){ ?>
        <div class="success"><?php echo $message; ?></div>
    <?php } ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Enter Full Name" autocomplete="off" required>
        <input type="email" name="email" placeholder="Enter Email" autocomplete="off" required>
        <div class="password-box">
        <input type="password" id="password" name="password" placeholder="Enter Password" autocomplete="new-password" required>
        <span class="toggle-password" onclick="togglePassword('password',this)">👁</span>
        </div>
        <div class="password-box">
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" autocomplete="new-password" required>
        <span class="toggle-password" onclick="togglePassword('confirm_password',this)">👁</span>
        </div>

        <button type="submit" name="register">Register</button>
    </form>

    <div class="login-link">
        Already have an account? <a href="index.php">Login</a>
    </div>
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