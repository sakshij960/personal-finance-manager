<?php
session_start();
include("db/connection.php");

// If user already logged in → redirect to dashboard
//if(isset($_SESSION['user_id'])){
  //  header("Location: dashboard.php");
   // exit();
//}

$error = "";

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);

        // If using password_hash()
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Personal Finance Manager</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body{
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #4e73df, #1cc88a);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box{
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .login-box h2{
            text-align: center;
            margin-bottom: 20px;
        }

        input{
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button{
            width: 100%;
            padding: 10px;
            background: #4e73df;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover{
            background: #2e59d9;
        }

        .error{
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .register-link{
            text-align: center;
            margin-top: 15px;
        }

        .register-link a{
            color: #4e73df;
            text-decoration: none;
        }

        .password-box{
    position:relative;
    width:100%;
    }

    .password-box input{
    width:100%;
    padding:10px;
    padding-right:40px;
    box-sizing:border-box;
    }

    .toggle-password{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
    }
    </style>
</head>
<body>

<div class="login-box">

    <?php
    if(isset($_GET['msg']) && $_GET['msg']=="reset_success"){
    echo "<p style='color:green;text-align:center;'>Password Reset Successful. Please login.</p>";
    }
    ?>

    <h2>Login</h2>

    <?php if($error != ""){ ?>
        <div class="error"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" autocomplete="off" required>
        <div class="password-box">
        <input type="password" id="password" name="password" placeholder="Enter Password" autocomplete="new-password" required>
        <span class="toggle-password" onclick="togglePassword('password',this)">👁</span>
        </div>
        <div style="text-align:right; margin-top:5px;">
        <a href="forgot_password.php">Forgot Password?</a>
        </div>
        <button type="submit" name="login">Login</button>
    </form>

    <div class="register-link">
        Don't have an account? <a href="register.php">Register</a>
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